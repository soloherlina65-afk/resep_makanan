(() => {
    const input = document.getElementById('recipe-search');
    const results = document.getElementById('recipe-autocomplete-results');

    if (!input || !results) {
        return;
    }

    let debounceTimer;
    let activeRequest;

    const closeResults = () => {
        results.hidden = true;
        input.setAttribute('aria-expanded', 'false');
        results.replaceChildren();
    };

    const showMessage = (message) => {
        const paragraph = document.createElement('p');
        paragraph.className = 'recipe-autocomplete-message';
        paragraph.textContent = message;
        results.replaceChildren(paragraph);
        results.hidden = false;
        input.setAttribute('aria-expanded', 'true');
    };

    const showResults = (recipes) => {
        results.replaceChildren();

        if (recipes.length === 0) {
            showMessage('Belum ada resep yang cocok.');
            return;
        }

        recipes.forEach((recipe) => {
            const link = document.createElement('a');
            link.className = 'recipe-autocomplete-result';
            link.href = recipe.url;
            link.setAttribute('role', 'option');

            if (recipe.image) {
                const image = document.createElement('img');
                image.src = recipe.image;
                image.alt = '';
                image.loading = 'lazy';
                link.appendChild(image);
            } else {
                const placeholder = document.createElement('span');
                placeholder.className = 'recipe-autocomplete-placeholder';
                placeholder.setAttribute('aria-hidden', 'true');
                placeholder.textContent = '🍲';
                link.appendChild(placeholder);
            }

            const copy = document.createElement('span');
            copy.className = 'recipe-autocomplete-copy';

            const name = document.createElement('strong');
            name.textContent = recipe.name;

            const category = document.createElement('small');
            category.textContent = recipe.category;

            copy.append(name, category);
            link.appendChild(copy);
            results.appendChild(link);
        });

        results.hidden = false;
        input.setAttribute('aria-expanded', 'true');
    };

    input.addEventListener('input', () => {
        window.clearTimeout(debounceTimer);

        if (activeRequest) {
            activeRequest.abort();
        }

        const query = input.value.trim();

        if (query.length < 2) {
            closeResults();
            return;
        }

        debounceTimer = window.setTimeout(async () => {
            activeRequest = new AbortController();
            const url = new URL(input.dataset.suggestionsUrl, window.location.origin);
            url.searchParams.set('q', query);

            try {
                const response = await fetch(url, {
                    headers: { Accept: 'application/json' },
                    signal: activeRequest.signal,
                });

                if (!response.ok) {
                    throw new Error(`Suggestion request failed with status ${response.status}`);
                }

                const recipes = await response.json();

                if (input.value.trim() === query) {
                    showResults(recipes);
                }
            } catch (error) {
                if (error.name === 'AbortError') {
                    return;
                }

                if (input.value.trim() === query) {
                    showMessage('Saran pencarian tidak tersedia. Tekan Enter untuk mencari.');
                }
            }
        }, 250);
    });

    input.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeResults();
            return;
        }

        if (event.key === 'ArrowDown' && !results.hidden) {
            const firstResult = results.querySelector('a');
            if (firstResult) {
                event.preventDefault();
                firstResult.focus();
            }
        }
    });

    results.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            input.focus();
            closeResults();
            return;
        }

        if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') {
            return;
        }

        const links = Array.from(results.querySelectorAll('a'));
        const currentIndex = links.indexOf(document.activeElement);
        const nextIndex = event.key === 'ArrowDown' ? currentIndex + 1 : currentIndex - 1;

        event.preventDefault();

        if (nextIndex < 0) {
            input.focus();
        } else if (links[nextIndex]) {
            links[nextIndex].focus();
        }
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('.search-autocomplete')) {
            closeResults();
        }
    });
})();

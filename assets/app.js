import './styles/app.css';
import './bootstrap';

document.addEventListener("DOMContentLoaded", function() {
    init_detail();
    init_filter();
    init_search();

});

function init_detail() {
    document.querySelectorAll('.show-movie-detail').forEach(item => {
        item.addEventListener('click', event => {
            event.stopPropagation();
            let url = event.target.getAttribute('data-url-detail');
            xhrCall('GET', url, view_modal);
            event.target.textContent = 'processing ...';
        });
    });
}

function init_search() {
    document.getElementById('button-search').addEventListener('click', event => {
        search_action(event);
    });

    document.getElementById('input-search').addEventListener('keypress', event => {
        if (event.key === "Enter") {
            search_action(event);
          }
    });
}

function init_filter() {
   
    document.querySelectorAll('.input-genre').forEach(item => {
        item.addEventListener('change', event => {
            document.getElementById('bloc-cards').innerHTML = '<p>Processing ....<p>';
            event.stopPropagation();
            let genres = [];
            let url = document.getElementById('filter-movie-by-genre').getAttribute('data-url');
            document.querySelectorAll('input[type="checkbox"]:checked').forEach(index => {
                genres.push(index.getAttribute('value'));
            });

            xhrCall('POST', url, view_list_movie, 'genres[]='+genres.join('&genres[]='));
        });
    });
}

function search_action(event) {
    document.getElementById('bloc-cards').innerHTML = '<p>Processing ....<p>';
    event.stopPropagation();
    let query = document.getElementById('input-search');
    let url = document.getElementById('search-movie').getAttribute('data-url');
    xhrCall('POST', url, view_list_movie, 'query='+query.value);
}

function xhrCall(method, url, callback, body = '' ,async = false) {
    const xhrRequest = new XMLHttpRequest();
    if(async === false && xhrRequest !== 4) {
        xhrRequest.abort();
    }
    xhrRequest.onreadystatechange = () => {
        if(xhrRequest.readyState === 4) {
            callback(xhrRequest.response);
        }
    }
    xhrRequest.open(method, url);
    xhrRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhrRequest.send(encodeURI(body));
}

function view_modal(response) {
    let parsedData = JSON.parse(response);
    let genres = 'Genres: ';
    let counter = 0;
    (parsedData.genres).forEach(genre => {
        genres = genres + (counter > 0 ? ', ' : ' ') + genre.name;
        counter = counter + 1;
    });
    document.getElementById('movie-player').src = parsedData.videoUrl;
    document.getElementById('movie-title').innerHTML = parsedData.title;
    document.getElementById('movie-overview').innerHTML = parsedData.overview;
    document.getElementById('movie-release-date').innerHTML = 'Date de Sortie : ' + parsedData.release_date.slice(0, 4);
    document.getElementById('movie-release-info').innerHTML = 'Votes : ' + parsedData.vote_count ;
    document.getElementById('movie-genres').innerHTML = genres;

    let modal = new bootstrap.Modal(document.getElementById('detailMovieModal'), {
        keyboard: false
      });
    modal.show();
    document.querySelectorAll('.show-movie-detail').forEach(item => {
        item.innerText  = 'Voir le details';
    });
}

function view_list_movie(response) {
    let parsedData = JSON.parse(response);
    document.getElementById('bloc-cards').innerHTML = parsedData.cards;
    init_detail();
}
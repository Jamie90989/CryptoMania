$(document).ready(function () {
    renderNews();
});

// Render news into the container
function renderNews() {
    const template = document.getElementById("news-template").innerHTML;

    fetchNews(function (news) {
        const rendered = Mustache.render(template, { news });
        document.getElementById("newsContainer").innerHTML = rendered;
    });
}

// Fetch news from CryptoCompare API
function fetchNews(callback) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "https://min-api.cryptocompare.com/data/v2/news/?lang=EN",
        success: function (response) {
            const news = response.Data.map(ex => ({
                title: ex.title,
                img: ex.imageurl || 'https://via.placeholder.com/150',
                text: ex.body.length > 150 ? ex.body.substring(0, 150) + '...' : ex.body,
                url: ex.url,
                source: ex.source
            }));
            callback(news);
        },
        error: function (err) {
            console.error("API error:", err);
            document.getElementById("newsContainer").innerHTML =
                "<p class='news-error-message'>Failed to load news. Please try again later.</p>";
        }
    });
}

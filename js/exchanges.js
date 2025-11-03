$(document).ready(function () {
    renderExchanges();
});

// Render exchanges into the container
function renderExchanges() {
    const template = document.getElementById("exchange-template").innerHTML;

    fetchExchanges(function (exchanges) {
        const rendered = Mustache.render(template, { exchanges });
        document.getElementById("exchangeContainer").innerHTML = rendered;
    });
}

// Fetch exchanges from CoinCap API
function fetchExchanges(callback) {
    $.ajax({
         type: "GET",
        dataType: "json",
        url: "https://rest.coincap.io/v3/assets?apiKey=bc2e17edb0b35af378060dae9c35a6aeea540fac4e2b7b463b185fd5339b81eb",
        success: function (response) {
            const exchanges = response.data.map(ex => ({
                rank: ex.rank,
                name: ex.name,
                volumeUsd: Number(ex.volumeUsd24Hr).toLocaleString(undefined, {maximumFractionDigits: 2}),
                exchangeUrl: "https://coincap.io"
            }));
            callback(exchanges);
        },
        error: function (err) {
            console.error("API error:", err);
            document.getElementById("exchangeContainer").innerHTML =
                "<p class='exchange-error-message'>Failed to load exchanges. Please try again later.</p>";
        }
    });
}

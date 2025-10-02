let assetsGlobal = [];

function render() {
    const template = document.getElementById("table").innerHTML;

    fetchfromapi(function (assets) {
        assetsGlobal = assets;
        const rendered = Mustache.render(template, { assets });
        document.getElementById("tableBody").innerHTML = rendered;

        document.querySelectorAll(".details-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                const index = Number(this.dataset.index) - 1;
                showDetails(assetsGlobal[index]);
            });
        });
    });
}

function showDetails(asset) {
    const modal = document.getElementById("modal");
    const modalDetails = document.getElementById("modalDetails");

    // Show coin info
    modalDetails.innerHTML = `
        <h2>${asset.name} (${asset.symbol})</h2>
        <p>Price: $${asset.price}</p>
        <p>Market Cap: $${asset.marketCap}</p>
        <p>Volume (24h): $${Number(asset.volumeUsd24Hr).toLocaleString(undefined, { maximumFractionDigits: 0 })}</p>
        <p>Supply: ${asset.supply}</p>
    `;

    // Show modal
    modal.style.display = "flex";

    // Fetch 7-day history
    console.log("fetching from " + asset.id);
    fetch(`https://rest.coincap.io/v3/assets/${asset.id}/history?interval=d1&apiKey=bc2e17edb0b35af378060dae9c35a6aeea540fac4e2b7b463b185fd5339b81eb&limit=10`)
        .then(res => res.json())
        .then(historyData => {
            const prices = historyData.data.slice(-7);
            const labels = prices.map(p => new Date(p.time).toLocaleDateString());
            const dataPoints = prices.map(p => Number(p.priceUsd));
            console.log(dataPoints);

            const ctx = document.getElementById("historyChart").getContext("2d");

            if (window.historyChartInstance) window.historyChartInstance.destroy();

            window.historyChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: `${asset.name} Price (Last 7 Days)`,
                        data: dataPoints,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { title: { display: true, text: 'Date' } },
                        y: { title: { display: true, text: 'Price USD' } }
                    }
                }
            });
        });

}

// Close modal
document.getElementById("closeModal").addEventListener("click", function () {
    document.getElementById("modal").style.display = "none";
});

// Initial render
// $(function() { render(); });


function fetchfromapi(callback) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "https://rest.coincap.io/v3/assets?apiKey=bc2e17edb0b35af378060dae9c35a6aeea540fac4e2b7b463b185fd5339b81eb&limit=10",
        success: function (data) {
            const assets = data.data.map((asset, i) => ({
                ...asset,

                row: i + 1,
                // format values so they're human-friendly
                price: Number(asset.priceUsd).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }),
                supply: Number(asset.supply).toLocaleString(),
                marketCap: Number(asset.marketCapUsd).toLocaleString(undefined, {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }),
                logo: `img/${asset.symbol.toLowerCase()}.png`
            }));
            callback(assets);
            $(".preloader").hide();
            console.log(assets);
        },
        error: function (err) {
            console.error("API error:", err);
        }
    });
}

$(document).ready(function () {
    render();   
});

$(document).on('click', '#buyButton', function () {
    const amount = parseFloat($('#buyAmount').val());
    if (!amount || amount <= 0) { alert('Enter valid amount'); return; }
    else {
        const coinName = $('#modalDetails h2').text().split(' (')[0];
        const symbol = $('#modalDetails h2').text().match(/\((.*)\)/)[1];
        const price = currentRawPrice;
        const coin_id = symbol.toLowerCase(); // or store API id if available

        console.log("ENTERED");

        $.post('php/ajax_wallet.php', {
            action: 'add',
            coin_id: coin_id,
            coin_name: coinName,
            amount: amount,
            price: price
        }, function (res) {
            if (res.success) {
                window.location.href = 'wallet.php'; // redirect
            } else if (res.error) {
                alert('Error: ' + res.error);
            }
        }, 'json');
    }


});

let currentRawPrice = 0; // <--- Add this at the top of the file

function showDetails(asset) {
    const modal = document.getElementById("modal");
    const modalDetails = document.getElementById("modalDetails");

    // ✅ Save raw price (convert back to number)
    currentRawPrice = Number(asset.priceUsd);

    // Show coin info
    modalDetails.innerHTML = `
        <h2>${asset.name} (${asset.symbol})</h2>
        <p>Price: $${asset.price}</p>
        <p>Market Cap: $${asset.marketCap}</p>
        <p>Volume (24h): $${Number(asset.volumeUsd24Hr).toLocaleString(undefined, { maximumFractionDigits: 0 })}</p>
        <p>Supply: ${asset.supply}</p>
    `;

    // ✅ Reset total cost text
    $('#totalCost').text('Total: $0.00');
    $('#buyAmount').val('');

    // Show modal
    modal.style.display = "flex";

    // Fetch 7-day history
    console.log("fetching from " + asset.id);
    fetch(`https://rest.coincap.io/v3/assets/${asset.id}/history?interval=d1&apiKey=bc2e17edb0b35af378060dae9c35a6aeea540fac4e2b7b463b185fd5339b81eb&limit=10`)
        .then(res => res.json())
        .then(historyData => {
            const prices = historyData.data.slice(-7);
            const labels = prices.map(p => new Date(p.time).toLocaleDateString());
            const dataPoints = prices.map(p => Number(p.priceUsd));
            console.log(dataPoints);

            const ctx = document.getElementById("historyChart").getContext("2d");

            if (window.historyChartInstance) window.historyChartInstance.destroy();

            window.historyChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: `${asset.name} Price (Last 7 Days)`,
                        data: dataPoints,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { title: { display: true, text: 'Date' } },
                        y: { title: { display: true, text: 'Price USD' } }
                    }
                }
            });
        });
}

// Update total as the user types
$(document).on('input', '#buyAmount', function () {
    const amount = parseFloat($(this).val()) || 0;
    const total = amount * currentRawPrice;
    $('#totalCost').text(`Total: $${total.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    })}`);
});



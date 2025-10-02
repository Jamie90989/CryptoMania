function loadWallet() {
    $.post('php/ajax_wallet.php', { action: 'get' }, function (data) {
        console.log("Wallet AJAX response:", data);
        if (data.error) { 
            alert(data.error); 
            return; 
        }

        // Format coins for template
        const coins = data.map(coin => ({
            ...coin,
            price: Number(coin.price).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }),
            amount: Number(coin.amount).toLocaleString(),
            total: (coin.amount * coin.price).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })
        }));

        const template = document.getElementById("walletTemplate").innerHTML;
        const rendered = Mustache.render(template, { coins });
        document.getElementById("walletBody").innerHTML = rendered;

        $('#walletContainer').show();
    }, 'json');
}

$(document).ready(function () {
    loadWallet();
});

let currentWalletCoinId = null;

// Open modal
$(document).on('click', '.updateCoin', function () {
    const row = $(this).closest('tr');
    currentWalletCoinId = row.data('coin');
    const currentAmount = row.find('.wallet-amount-display').text();

    $('#walletEditAmount').val(currentAmount);
    $('#walletEditModal').fadeIn();
});

// Close modal
$(document).on('click', '.wallet-edit-close', function () {
    $('#walletEditModal').fadeOut();
});

// Save changes
$(document).on('click', '#walletSaveEditButton', function () {
    const amount = $('#walletEditAmount').val();

    $.post('php/ajax_wallet.php', { action: 'update', coin_id: currentWalletCoinId, amount }, function(res) {
        if (res.success) {
            // Update table display
            $(`tr[data-coin="${currentWalletCoinId}"]`).find('.wallet-amount-display').text(amount);
            $('#walletEditModal').fadeOut();
            showWalletNotification('Coin updated successfully' , 'success');
            loadWallet();
        } else {
             showWalletNotification('Failed' , 'error');
        }
    }, 'json');
});

$(document).on('click', '.deleteCoin', function () {
    const row = $(this).closest('tr');
    const coin_id = row.data('coin');
    $.post('php/ajax_wallet.php', { action: 'delete', coin_id }, function (res) {
        if (res.success) {
            showWalletNotification('Coin deleted successfully' , 'delete');
            loadWallet();
        }
        else{
             showWalletNotification('Failed' , 'error');
        }
    }, 'json');
});

function showWalletNotification(message, type = 'success', duration = 3000) {
    const notif = $('#walletNotification');

    // Remove existing type classes
    notif.removeClass('wallet-notif-success wallet-notif-error wallet-notif-delete');

    // Add the correct type class
    if(type === 'success') notif.addClass('wallet-notif-success');
    else if(type === 'delete') notif.addClass('wallet-notif-delete');
    else if(type === 'error') notif.addClass('wallet-notif-error');

    // Set text and show
    notif.text(message).fadeIn();

    // Hide after duration
    setTimeout(() => {
        notif.fadeOut();
    }, duration);
}
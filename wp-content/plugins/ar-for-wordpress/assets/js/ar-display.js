function copyToClipboard(ID) {
    var copyText = document.getElementById(ID);
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");
}
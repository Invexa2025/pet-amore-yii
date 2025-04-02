function WebSocketPrinter(options) {
    var defaults = {
        url: "ws://127.0.0.1:12212/printer",
        selectedPrinter: null,
        onConnect: function () {},
        onDisconnect: function () {},
        onUpdate: function () {},
        onError: function () {}
    };

    var settings = Object.assign({}, defaults, options);
    var websocket;
    var connected = false;

    var onMessage = function (evt) {
        var data = JSON.parse(evt.data);
        if (data.printers) {
            settings.onUpdate(data.printers); 
        }
    };

    var onConnect = function () {
        connected = true;
        settings.onConnect();
    };

    var onError = function () {
        connected = false;
        settings.onError();
    };

    var onDisconnect = function () {
        connected = false;
        settings.onDisconnect();
        reconnect();
    };

    var connect = function () {
        websocket = new WebSocket(settings.url);
        websocket.onopen = onConnect;
        websocket.onclose = onDisconnect;
        websocket.onmessage = onMessage;
        websocket.onerror = onError;
    };

    var reconnect = function () {
        setTimeout(connect, 3000);
    };

    this.getPrinters = function () {
        if (connected) {
            websocket.send(JSON.stringify({ command: "list_printers" }));
        }
    };

    this.selectPrinter = function (printerName) {
        settings.selectedPrinter = printerName;
    };

    this.submit = function (data) {
        if (!settings.selectedPrinter) {
            console.error("Please choose your printer first!");
            return;
        }

        var printData = {
            printer: settings.selectedPrinter,
            data: data
        };

        websocket.send(JSON.stringify(printData));
    };

    this.isConnected = function () {
        return connected;
    };

    connect();
}

/* client code */
Comet = {
    sleep: 1000,
    _subscribed: {},
    _timeout: undefined,
    _baseUrl: "server/objects/comet.php",
    _params: '',
    _keyParams: 'subscribed',
 
    subscribe: function(id, callback) {
        Comet._subscribed[id] = {
            cb: callback,
            timestamp: Comet._getCurrentTimestamp()
        };
        return Comet;
    },
 
    _refresh: function() {
        Comet._timeout = setTimeout(function() { Comet.run(); }, Comet.sleep);
    },
 
    init: function(baseUrl) {
        if (baseUrl !== undefined) Comet._baseUrl = baseUrl;
    },
 
    _getCurrentTimestamp: function() {
        return Math.round(new Date().getTime() / 1000);
    },
 
    run: function() {
        /* build url */
        var url = Comet._baseUrl + '?' + Comet._params;
        for (var id in Comet._subscribed) {         
            url += '&' + Comet._keyParams+ '[' + id + ']=' + Comet._subscribed[id].timestamp;
        }
        url += '&' + Comet._getCurrentTimestamp();

        /* get data from server by url */
        $.getJSON(url, function(data) {
            switch(data.status) {
                case 0: // no change
                    Comet._refresh();
                    break;
                case 1: // trigger
                    for (var id in data.result) {
                        Comet._subscribed[id].timestamp = data.result[id];
                        Comet._subscribed[id].cb(data.result);
                    }
                    Comet._refresh();
                    break;
            }
        }); 
    },
 
    publish: function(id) {
        var urlPublish = Comet._baseUrl + '?' + Comet._params + '&published=' + id;    
        $.getJSON(urlPublish, function (data) {
            console.log(data);
        });
    }
};
/*
YUI 3.10.3 (build 2fb5187)
Copyright 2013 Yahoo! Inc. All rights reserved.
Licensed under the BSD License.
http://yuilibrary.com/license/
*/

YUI.add('io-xdr', function (Y, NAME) {

/**
Extends IO to provide an alternate, Flash transport, for making
cross-domain requests.
@module io
@submodule io-xdr
@for IO
**/

// Helpful resources when working with the mess that is XDomainRequest:
// http://www.cypressnorth.com/blog/web-programming-and-development/internet-explorer-aborting-ajax-requests-fixed/
// http://blogs.msdn.com/b/ieinternals/archive/2010/05/13/xdomainrequest-restrictions-limitations-and-workarounds.aspx

/**
Fires when the XDR transport is ready for use.
@event io:xdrReady
**/
var E_XDR_READY = Y.publish('io:xdrReady', { fireOnce: true }),

/**
Map of stored configuration objects when using
Flash as the transport for cross-domain requests.

@property _cB
@private
@type {Object}
**/
_cB = {},

/**
Map of transaction simulated readyState values
when XDomainRequest is the transport.

@property _rS
@private
@type {Object}
**/
_rS = {},

// Document reference
d = Y.config.doc,
// Window reference
w = Y.config.win,
// XDomainRequest cross-origin request detection
xdr = w && w.XDomainRequest;

/**
Method that creates the Flash transport swf.

@method _swf
@private
@param {String} uri - location of io.swf.
@param {String} yid - YUI sandbox id.
@param {String} yid - IO instance id.
**/
function _swf(uri, yid, uid) {
    var o = '<object id="io_swf" type="application/x-shockwave-flash" data="' +
            uri + '" width="0" height="0">' +
            '<param name="movie" value="' + uri + '">' +
            '<param name="FlashVars" value="yid=' + yid + '&uid=' + uid + '">' +
            '<param name="allowScriptAccess" value="always">' +
            '</object>',
        c = d.createElement('div');

    d.body.appendChild(c);
    c.innerHTML = o;
}

/**
Creates a response object for XDR transactions, for success
and failure cases.

@method _data
@private
@param {Object} o - Transaction object generated by _create() in io-base.
@param {Boolean} u - Configuration xdr.use.
@param {Boolean} d - Configuration xdr.dataType.

@return {Object}
**/
function _data(o, u, d) {
    if (u === 'flash') {
        o.c.responseText = decodeURI(o.c.responseText);
    }
    if (d === 'xml') {
        o.c.responseXML = Y.DataType.XML.parse(o.c.responseText);
    }

    return o;
}

/**
Method for intiating an XDR transaction abort.

@method _abort
@private
@param {Object} o - Transaction object generated by _create() in io-base.
@param {Object} c - configuration object for the transaction.
**/
function _abort(o, c) {
    return o.c.abort(o.id, c);
}

/**
Method for determining if an XDR transaction has completed
and all data are received.

@method _isInProgress
@private
@param {Object} o - Transaction object generated by _create() in io-base.
**/
function _isInProgress(o) {
    return xdr ? _rS[o.id] !== 4 : o.c.isInProgress(o.id);
}

Y.mix(Y.IO.prototype, {

    /**
    Map of io transports.

    @property _transport
    @private
    @type {Object}
    **/
    _transport: {},

    /**
    Sets event handlers for XDomainRequest transactions.

    @method _ieEvt
    @private
    @static
    @param {Object} o - Transaction object generated by _create() in io-base.
    @param {Object} c - configuration object for the transaction.
    **/
    _ieEvt: function(o, c) {
        var io = this,
            i = o.id,
            t = 'timeout';

        o.c.onprogress = function() { _rS[i] = 3; };
        o.c.onload = function() {
            _rS[i] = 4;
            io.xdrResponse('success', o, c);
        };
        o.c.onerror = function() {
            _rS[i] = 4;
            io.xdrResponse('failure', o, c);
        };
        o.c.ontimeout = function() {
            _rS[i] = 4;
            io.xdrResponse(t, o, c);
        };
        o.c[t] = c[t] || 0;
    },

    /**
    Method for accessing the transport's interface for making a
    cross-domain transaction.

    @method xdr
    @param {String} uri - qualified path to transaction resource.
    @param {Object} o - Transaction object generated by _create() in io-base.
    @param {Object} c - configuration object for the transaction.
    **/
    xdr: function(uri, o, c) {
        var io = this;

        if (c.xdr.use === 'flash') {
            // The configuration object cannot be serialized safely
            // across Flash's ExternalInterface.
            _cB[o.id] = c;
            w.setTimeout(function() {
                try {
                    o.c.send(uri, { id: o.id,
                                    uid: o.uid,
                                    method: c.method,
                                    data: c.data,
                                    headers: c.headers });
                }
                catch(e) {
                    io.xdrResponse('transport error', o, c);
                    delete _cB[o.id];
                }
            }, Y.io.xdr.delay);
        }
        else if (xdr) {
            io._ieEvt(o, c);
            o.c.open(c.method || 'GET', uri);

            // Make async to protect against IE 8 oddities.
            setTimeout(function() {
                o.c.send(c.data);
            }, 0);
        }
        else {
            o.c.send(uri, o, c);
        }

        return {
            id: o.id,
            abort: function() {
                return o.c ? _abort(o, c) : false;
            },
            isInProgress: function() {
                return o.c ? _isInProgress(o.id) : false;
            },
            io: io
        };
    },

    /**
    Response controller for cross-domain requests when using the
    Flash transport or IE8's XDomainRequest object.

    @method xdrResponse
    @param {String} e Event name
    @param {Object} o Transaction object generated by _create() in io-base.
    @param {Object} c Configuration object for the transaction.
    @return {Object}
    **/
    xdrResponse: function(e, o, c) {
        c = _cB[o.id] ? _cB[o.id] : c;
        var io = this,
            m = xdr ? _rS : _cB,
            u = c.xdr.use,
            d = c.xdr.dataType;

        switch (e) {
            case 'start':
                io.start(o, c);
                break;
           //case 'complete':
                //This case is not used by Flash or XDomainRequest.
                //io.complete(o, c);
                //break;
            case 'success':
                io.success(_data(o, u, d), c);
                delete m[o.id];
                break;
            case 'timeout':
            case 'abort':
            case 'transport error':
                o.c = { status: 0, statusText: e };
            case 'failure':
                io.failure(_data(o, u, d), c);
                delete m[o.id];
                break;
        }
    },

    /**
    Fires event "io:xdrReady"

    @method _xdrReady
    @private
    @param {Number} yid - YUI sandbox id.
    @param {Number} uid - IO instance id.
    **/
    _xdrReady: function(yid, uid) {
        Y.fire(E_XDR_READY, yid, uid);
    },

    /**
    Initializes the desired transport.

    @method transport
    @param {Object} o - object of transport configurations.
    **/
    transport: function(c) {
        if (c.id === 'flash') {
            _swf(Y.UA.ie ? c.src + '?d=' + new Date().valueOf().toString() : c.src, Y.id, c.uid);
            Y.IO.transports.flash = function() { return d.getElementById('io_swf'); };
        }
    }
});

/**
Fires event "io:xdrReady"

@method xdrReady
@protected
@static
@param {Number} yid - YUI sandbox id.
@param {Number} uid - IO instance id.
**/
Y.io.xdrReady = function(yid, uid){
    var io = Y.io._map[uid];
    Y.io.xdr.delay = 0;
    io._xdrReady.apply(io, [yid, uid]);
};

Y.io.xdrResponse = function(e, o, c){
    var io = Y.io._map[o.uid];
    io.xdrResponse.apply(io, [e, o, c]);
};

Y.io.transport = function(c){
    var io = Y.io._map['io:0'] || new Y.IO();
    c.uid = io._uid;
    io.transport.apply(io, [c]);
};

/**
Delay value to calling the Flash transport, in the
event io.swf has not finished loading.  Once the E_XDR_READY
event is fired, this value will be set to 0.

@property delay
@static
@type {Number}
**/
Y.io.xdr = { delay : 100 };


}, '3.10.3', {"requires": ["io-base", "datatype-xml-parse"]});

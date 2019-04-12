var kml = new function() {
        this.addLayer = function(a, c, b, d, f, e, g) {
            var k = new ol.format.KML({
                extractStyles: !1
            });
            ubicaciones.addLayer(a, k, c, b, d, f, e, g)
        }
    },
    gml = new function() {
        this.addLayer = function(a, c, b, d, f, e, g) {
            var k = new ol.format.GML2({
                srsName: "EPSG:4326"
            });
            ubicaciones.addLayer(a, k, c, b, d, f, e, g)
        }
    },
    geojson = new function() {
        this.procesarUbicacionBase = function(a, c, b, d, f, e) {
            "FeatureCollection" == c.type ? ubicaciones.geojson.procesarFeatureCollection(a, c, b, d) : "Feature" == c.type ? ubicaciones.geojson.procesarFeature(a,
                c, b, d) : ubicaciones.geojson.procesarGeometry(a, c, b, d);
            e ? $("#" + a.id + "-ul li").each(function() {
                $(this).attr(ubicaciones.suffix + "-clase") == b && $(this).click(e)
            }) : f ? $("#" + a.id + "-ul li").each(function() {
                $(this).attr(ubicaciones.suffix + "-clase") == b && $(this).click(ubicaciones.geojson.borrarYSeleccionarUbicacion)
            }) : $("#" + a.id + "-ul li").each(function() {
                $(this).attr(ubicaciones.suffix + "-clase") == b && $(this).click(ubicaciones.geojson.seleccionarUbicacion)
            });
            $("#" + a.id + "-ul").css("display", "block");
            $("#" + a.id + "-ul").resaltarUl($("#" +
                a.id).val().trim(), ubicaciones.suffix + "-resaltado")
        };
        this.procesarUbicacion = function(a, c, b, d, f) {
            $("#" + a.id + "-ul").empty();
            ubicaciones.geojson.procesarUbicacionBase(a, c, b, !1, d, f)
        };
        this.procesarUbicacionClase = function(a, c, b, d, f, e) {
            $("#" + a.id + "-ul li").each(function() {
                $(this).attr(ubicaciones.suffix + "-clase") == b && $(this).remove()
            });
            ubicaciones.geojson.procesarUbicacionBase(a, c, b, d, f, e)
        };
        this.procesarFeatureCollection = function(a, c, b, d) {
            if (d)
                for (var f = c.features.length - 1; 0 <= f; f--) ubicaciones.geojson.procesarFeature(a,
                    c.features[f], b, d);
            else
                for (f = 0; f < c.features.length; f++) ubicaciones.geojson.procesarFeature(a, c.features[f], b, d)
        };
        this.procesarFeature = function(a, c, b, d) {
            var f = JSON.stringify(c).replace(RegExp('"', "g"), "'"),
                e = ubicaciones.subtipo_direcciones;
            "lugar" == b || "ubicacion" == b ? e = "(" + c.properties.subtipo.toUpperCase() + ")" : "direccion" == b && (e = "(" + c.properties.subtipo.toUpperCase() + ")");
            if ("direccion" != b && "ubicacion" != b || "RANGOS ALTURAS" != c.properties.subtipo.toUpperCase()) c = c.properties.name.replace("INTERSECCI\u00d3N",
                "y"), b = '<li class="' + ubicaciones.suffix + '-li" label-value="' + c.replace(RegExp('"', "g"), "&quot;") + '" hidden-value="' + f + '" ' + ubicaciones.suffix + '-clase="' + b + '" tabindex="-1" onkeyup="ubicaciones.likeypress(event.keyCode,event.target);" onmouseover="ubicaciones.lihover(event.target);"><span class="' + ubicaciones.suffix + '-li-principal">' + c + '</span><br/><span class="' + ubicaciones.suffix + '-li-auxiliar">' + e + "</span></li>";
            else {
                b = '<li class="' + ubicaciones.suffix + '-li noselect" label-value="" hidden-value="' +
                    f + '" ' + ubicaciones.suffix + '-clase="' + b + '" tabindex="-1"><span class="' + ubicaciones.suffix + '-li-preprincipal">Alturas comprendidas para la calle </span><span class="' + ubicaciones.suffix + '-li-principal">' + c.properties.name + "</span>";
                for (i = 0; i < parseInt(c.properties.cantidadRangosAlturas); i++) b += '<br/><span class="' + ubicaciones.suffix + '-li-auxiliar">' + c.properties["rango" + i] + "</span>";
                b += "</li>"
            }
            d ? $("#" + a.id + "-ul").prepend(b) : $("#" + a.id + "-ul").append(b)
        };
        this.procesarGeometry = function(a, c, b, d) {
            var f =
                JSON.stringify(c).replace(RegExp('"', "g"), "'"),
                e = ubicaciones.subtipo_direcciones;
            "lugar" == b || "ubicacion" == b ? e = "(" + c.features[i].properties.subtipo.toUpperCase() + ")" : "direccion" == b && (e = "(" + c.features[i].properties.subtipo.toUpperCase() + ")");
            d ? $("#" + a.id + "-ul").prepend('<li class="' + ubicaciones.suffix + '-li" label-value="' + c.features[i].properties.name.replace(RegExp('"', "g"), '"') + '" hidden-value="' + f + '" ' + ubicaciones.suffix + '-clase="' + b + '" tabindex="-1" onkeypress="ubicaciones.likeypress(event.keyCode,event.target);" onmouseover="ubicaciones.lihover(event.target);"><span class="' +
                ubicaciones.suffix + '-li-principal">' + c.features[i].properties.name + '</span><br/><span class="' + ubicaciones.suffix + '-li-auxiliar">' + e + "</span></li>") : $("#" + a.id + "-ul").append('<li class="' + ubicaciones.suffix + '-li" label-value="' + c.features[i].properties.name.replace(RegExp('"', "g"), '"') + '" hidden-value="' + f + '" ' + ubicaciones.suffix + '-clase="' + b + '" tabindex="-1" onkeypress="ubicaciones.likeypress(event.keyCode,event.target);" onmouseover="ubicaciones.lihover(event.target);"><span class="' + ubicaciones.suffix +
                '-li-principal">' + c.features[i].properties.name + '</span><br/><span class="' + ubicaciones.suffix + '-li-auxiliar">' + e + "</span></li>")
        };
        this.seleccionarUbicacion = function(a, c, b) {
            var d = $(a.currentTarget).parent().attr("control-rel"),
                f = $(a.currentTarget).attr("hidden-value"),
                e = ubicaciones.parseJSON(f);
            c = ubicaciones.recuperarParam(d);
            if ("CALLE" == e.properties.subtipo && c.filtro && c.filtro.mostrarReferenciasAlturas) {
                var g = ubicaciones.armarUrlRefAlturas(c.filtro, e.properties.id);
                $("#" + d + "-ul").css("display", "none");
                $.ajax({
                    type: "GET",
                    url: g,
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function(a) {
                        var b = '<li class="' + ubicaciones.suffix + '-li noselect" label-value="" hidden-value="' + f + '" tabindex="-1"><span class="' + ubicaciones.suffix + '-li-preprincipal">Alturas comprendidas para la calle </span><span class="' + ubicaciones.suffix + '-li-principal">' + e.properties.name + "</span>";
                        for (i = 0; i < parseInt(a.length); i++) {
                            var c = "Desde: " + a[i].alturaDesde + " Hasta: " + a[i].alturaHasta;
                            a[i].bis && (c += " Bis");
                            a[i].letra && "" != a[i].letra && (c = c + " Letra: " + a[i].letra);
                            b += '<br/><span class="' + ubicaciones.suffix + '-li-auxiliar">' + c + "</span>"
                        }
                        b += "</li>";
                        $("#" + d + "-ul").empty();
                        $("#" + d + "-ul").append(b);
                        $("#" + d + "-ul").css("display", "block")
                    },
                    error: function(a) {
                        console.log(a)
                    }
                })
            }
            "RANGOS ALTURAS" != e.properties.subtipo && ($("#" + d).val($(a.currentTarget).attr("label-value").replace("INTERSECCI\u00d3N", "y")), $("#" + d + "-hidden").val(f), a = $(a.currentTarget).parent().attr("mapa-rel"), "" != a && (b && (b = $("#" + a).data("map"), ubicaciones.clearMap(b)),
                b = $("#" + a).data("map"), ubicaciones.geojson.dibujarEnMapa(e, b, null, c.mapa.clustering), ubicaciones.geojson.centrarMapa(e, b)), $("#" + d + "-ul").css("display", "none"))
        };
        this.borrarYSeleccionarUbicacion = function(a, c) {
            ubicaciones.geojson.seleccionarUbicacion(a, c, !0)
        };
        this.writeFeaturesInControl = function(a, c, b) {
            var d = [];
            c.getFeatures().forEach(function(a) {
                d.push(a)
            });
            a && d.push(a);
            1 < d.length ? b.val((new ol.format.GeoJSON).writeFeatures(d)) : b.val((new ol.format.GeoJSON).writeFeature(d[0]));
            return d
        };
        this.dibujarEnMapa =
            function(a, c, b, d) {
                b || (b = "layerFeatures");
                var f = [];
                if ("FeatureCollection" == a.type)
                    for (var f = (new ol.format.GeoJSON).readFeatures(a), e = 0; e < a.features.length; e++) {
                        var g = null;
                        a.features[e].properties && a.features[e].properties.id && (g = a.features[e].properties.id);
                        null == g && (g = JSON.stringify(a.features[e].geometry.coordinates));
                        f[e].setId(g)
                    } else if ("Feature" == a.type) {
                        g = null;
                        a.properties && a.properties.id && (g = a.properties.id);
                        var k = null;
                        c.getLayers().forEach(function(a) {
                            a.get("id") == b && (k = a)
                        });
                        f = 0;
                        null != k &&
                            (f = d ? k.getSource().getSource().getFeatures().length : k.getSource().getFeatures().length, g = null == g ? JSON.stringify(a.geometry.coordinates) + "_" + f : g + "_" + f);
                        f = (new ol.format.GeoJSON).readFeatures(a);
                        0 < f.length && null != g && f[0].setId(g)
                    } else f = [new ol.Feature((new ol.format.GeoJSON).readGeometry(a))];
                if (0 < f.length) {
                    for (e = 0; e < f.length; e++) a = f[e], g = a.getProperties(), g[ubicaciones.atributoMapaId] = c.getTarget(), g[ubicaciones.atributoTitulo] = ubicaciones.atributoTituloDefault, a.setProperties(g);
                    new ol.source.Vector({
                        features: f
                    });
                    k = null;
                    c.getLayers().forEach(function(a) {
                        a.get("id") == b && (k = a)
                    });
                    null != k && (d ? k.getSource().getSource().addFeatures(f) : k.getSource().addFeatures(f))
                }
                return f
            };
        this.centrarMapa = function(a, c) {
            if (a && "FeatureCollection" != a.type)
                if ("Feature" == a.type) ubicaciones.geojson.centrarMapa(a.geometry, c);
                else if ("GeometryCollection" != a.type)
                if ("Point" == a.type) ubicaciones.centrarMapa(a.coordinates, c);
                else {
                    var b = new ol.Feature((new ol.format.GeoJSON).readGeometry(a));
                    ubicaciones.centrarMapaEnGeometry(b.getGeometry(),
                        c)
                }
        };
        this.addLayer = function(a, c, b, d, f, e, g) {
            var k = new ol.format.GeoJSON;
            $.ajax({
                type: "GET",
                url: a,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(a) {
                    a = k.readFeatures(a);
                    var d = ubicaciones.styleDraw;
                    e instanceof ol.style.Style && (d = e);
                    for (var d = new ol.layer.Vector({
                            source: new ol.source.Vector,
                            style: d,
                            id: f
                        }), g = 0; g < a.length; g++) {
                        var p = a[g],
                            m = p.getProperties();
                        m[ubicaciones.atributoMapaId] = c.getTarget();
                        m[ubicaciones.atributoTitulo] = b;
                        p.setProperties(m)
                    }
                    d.getSource().addFeatures(a);
                    c.addLayer(d);
                    e instanceof ol.style.Style || ubicaciones.setStyleInLayer(e, c, f)
                },
                error: function(a) {
                    console.log(a)
                }
            })
        }
    },
    styleMap = {
        fill: function(a) {
            if ("string" == typeof a || Array.isArray(a)) a = {
                color: a
            };
            return new ol.style.Fill(a)
        },
        stroke: function(a) {
            "string" == typeof a || Array.isArray(a) ? a = {
                color: a
            } : "number" == typeof a && (a = {
                width: a
            });
            return new ol.style.Stroke(a)
        },
        text: function(a) {
            "string" == typeof a && (a = {
                text: a
            });
            a.fill && (a.fill = styleMap.fill(a.fill));
            a.stroke && (a.stroke = styleMap.stroke(a.stroke));
            return new ol.style.Text(a)
        },
        circle: function(a) {
            a.fill && (a.fill = styleMap.fill(a.fill));
            a.stroke && (a.stroke = styleMap.stroke(a.stroke));
            return new ol.style.Circle(a)
        },
        icon: function(a) {
            return new ol.style.Icon(a)
        },
        image: function(a) {
            return "undefined" !== typeof a.radius ? styleMap.circle(a) : styleMap.icon(a)
        }
    };

function makeStyle(a) {
    if (Array.isArray(a)) return a.map(function(a) {
        return makeStyle(a)
    });
    var c = {};
    Object.keys(a).forEach(function(b) {
        var d = a[b];
        c[b] = styleMap[b] ? new styleMap[b](d) : d
    });
    return new ol.style.Style(c)
}
var ubicaciones = new function() {
    this.suffix = "ubicaciones";
    this.mensajeResultadoNoEncontrado = "No se encontraron resultados para la b\u00fasqueda";
    this.url_servicio_dir = "http://t-ws.rosario.gov.ar/ubicaciones/public/geojson/direcciones";
    this.url_servicio_lugares = "http://t-ws.rosario.gov.ar/ubicaciones/public/geojson/lugares";
    this.url_servicio_ubicaciones = "http://t-ws.rosario.gov.ar/ubicaciones/public/geojson/ubicaciones";
    this.url_servicio_dir_punto = "http://t-ws.rosario.gov.ar/ubicaciones/public/direccion/punto";
    this.url_servicio_ref_alturas = "http://t-ws.rosario.gov.ar/ubicaciones/public/referenciaalturas";
    this.subtipo_direcciones = "(DIRECCCI\u00d3N)";
    this.style_map_default = {
        width: "50%",
        heigth: "400px"
    };
    this.styleCache = {};
    "undefined" !== typeof ol && (this.styleDraw = new ol.style.Style({
        fill: new ol.style.Fill({
            color: "rgba(255, 255, 255, 0.3)"
        }),
        stroke: new ol.style.Stroke({
            color: "#E95F38",
            width: 2
        }),
        image: new ol.style.Icon({
            src: "img/boton-pin.png",
            scale: .6,
            offset: [0, 20]
        })
    }), this.styleInteractionCluster = new ol.style.Style({
        image: new ol.style.Icon({
            src: "img/boton-pin.png",
            scale: .6,
            offset: [0, 20]
        }),
        stroke: new ol.style.Stroke({
            color: "#fff",
            width: 1
        })
    }), this.getStyleCluster = function(a, c, b) {
        a = a.get("features").length;
        c = ubicaciones.styleCache[a];
        if (!c) {
            c = 25 < a ? "192,0,0" : 8 < a ? "255,128,0" : "0,128,0";
            var d = Math.max(8, Math.min(.75 * a, 20)),
                f = 2 * Math.PI * d / 6,
                f = [0, f, f, f, f, f, f];
            c = 1 == a ? b ? b : ubicaciones.styleDraw : ubicaciones.styleCache[a] = [new ol.style.Style({
                image: new ol.style.Circle({
                    radius: d,
                    stroke: new ol.style.Stroke({
                        color: "rgba(" + c + ",0.5)",
                        width: 15,
                        lineDash: f
                    }),
                    fill: new ol.style.Fill({
                        color: "rgba(" +
                            c + ",1)"
                    })
                }),
                text: new ol.style.Text({
                    text: a.toString(),
                    fill: new ol.style.Fill({
                        color: "#fff"
                    })
                }),
                zIndex: 10
            })]
        }
        return c
    });
    this.clonarStyle = function(a) {
        var c;
        if (Array.isArray(a)) return a.map(function(a) {
            return ubicaciones.clonarStyle(a)
        });
        c = null;
        a.getFill && (c = a.getFill());
        var b = null;
        a.getImage && (b = a.getImage());
        var d = null;
        a.getStroke && (d = a.getStroke());
        var f = null;
        a.getText && (f = a.getText());
        c = new ol.style.Style({
            fill: c,
            stroke: d,
            image: b,
            text: f
        });
        a.getGeometry && c.setGeometry(a.getGeometry());
        a.getZIndex &&
            c.setZIndex(a.getZIndex());
        return c
    };
    this.typeOf = function(a) {
        return {}.toString.call(a).match(/\s([a-zA-Z]+)/)[1].toLowerCase()
    };
    this.cloneObject = function(a) {
        var c = ubicaciones.typeOf(a);
        if ("object" == c || "array" == c) {
            if (a.clone) return a.clone();
            var c = "array" == c ? [] : {},
                b;
            for (b in a) c[b] = ubicaciones.cloneObject(a[b]);
            return c
        }
        return a
    };
    this.deselectAllFeatures = function(a) {
        a = $("#" + a).data("map").getInteractions().getArray();
        for (var c = 0; c < a.length; c++) {
            var b = a[c];
            if (b instanceof ol.interaction.Select) {
                for (var d =
                        0; d < b.getFeatures().getArray().length; d++) {
                    var f = b.getFeatures().getArray()[d],
                        e = f.getStyle(),
                        e = ubicaciones.cloneObject(e),
                        e = ubicaciones.cambiarStyleEnDeselect(e);
                    f.setStyle(e)
                }
                b.getFeatures().clear()
            }
        }
    };
    this.cambiarStyleEnSelect = function(a) {
        if ("array" == ubicaciones.typeOf(a)) {
            var c = [],
                b;
            for (b in a) c[b] = ubicaciones.cambiarStyleEnSelect(a[b]);
            return c
        }
        a.getImage() && (c = a.getImage().getScale(), c += .3, a.getImage().setScale(c));
        return a
    };
    this.cambiarStyleEnDeselect = function(a) {
        if ("array" == ubicaciones.typeOf(a)) {
            var c = [],
                b;
            for (b in a) c[b] = ubicaciones.cambiarStyleEnDeselect(a[b]);
            return c
        }
        a.getImage() && (c = a.getImage().getScale(), c -= .3, a.getImage().setScale(c));
        return a
    };
    this.format_default = "geojson";
    this.srsName = "EPSG:4326";
    this.srsNameCRS84 = "CRS:84";
    this.validChar = function(a) {
        return 47 < a && 58 > a || 32 == a || 64 < a && 91 > a || 95 < a && 112 > a || 185 < a && 193 > a || 8 == a || 0 == a || 229 == a || 218 < a && 223 > a
    };
    this.isMoveKey = function(a) {
        return 38 == a || 40 == a ? !0 : !1
    };
    this.parseJSON = function(a) {
        if ("" == a) return null;
        a = a.replace(RegExp('"', "g"), '\\"');
        a =
            a.replace(RegExp("'", "g"), '"');
        return JSON.parse(a)
    };
    this.likeypress = function(a, c) {
        40 == a ? $(c).next().focus() : 38 == a ? $(c).prev().focus() : 13 == a && $(c).click();
        return !1
    };
    this.lihover = function(a) {
        $("." + ubicaciones.suffix + "-li:focus").each(function() {
            $(this).blur()
        })
    };
    this.isDireccion = function(a) {
        var c = new BuscarDireccion;
        a = a.split(" ");
        for (var b = 0; b < a.length; b++) {
            var d = a[b];
            if (c.isConInterseccion()) c.setInterseccion(c.getInterseccion().trim() + d.trim());
            else if (isNaN(parseFloat(d))) "BIS" == d.trim().toUpperCase() ?
                c.setBis(!0) : "Y" == d.trim().toUpperCase() || "INTERSECCION" == d.trim().toUpperCase() || "INTERSECCI\u00d3N" == d.trim().toUpperCase() ? (c.setConInterseccion(!0), null != c.getNumero() && (c.setCalle(c.getCalle().trim() + " " + c.getNumero() + " " + d.trim()), c.setNumero(null))) : 1 == d.length ? ("" == !c.getLetra() && c.setCalle(c.getCalle().trim() + " " + c.getLetra()), c.setLetra(d)) : null != c.getNumero() ? (c.setCalle(c.getCalle().trim() + " " + c.getNumero() + " " + d.trim()), c.setNumero(null)) : c.setCalle("" == c.getCalle() ? d.trim() : c.getCalle() +
                    " " + d.trim());
            else {
                var f = parseInt(d);
                0 != f && (null != c.getNumero() && 0 != c.getNumero() && c.setCalle(c.getCalle().trim(), d.trim()), c.setNumero(f))
            }
        }
        return c.isConInterseccion() || null != c.getNumero()
    };
    this.recuperarParam = function(a) {
        a = $("#" + a + "-ul").attr("param-rel");
        return JSON.parse(a)
    };
    this.armarUrlDir = function(a) {
        return a.filtro && a.filtro.url ? a.filtro.url : ubicaciones.url_servicio_dir
    };
    this.armarUrlDirPunto = function(a, c, b) {
        var d = ubicaciones.url_servicio_dir_punto;
        a.mapa && a.mapa.mostrar && "direccion" == a.mapa.mostrar &&
            a.mapa.url_buscar && (d = a.mapa.url_buscar);
        return d + ("/" + c + "/" + b + "/")
    };
    this.armarUrlRefAlturas = function(a, c) {
        var b = ubicaciones.url_servicio_ref_alturas;
        a && a.urlReferenciasAlturas && (b = a.urlReferenciasAlturas);
        return b + ("/" + c + "/true")
    };
    this.armarUrlLugares = function(a) {
        return ubicaciones.armarUrlLugaresConTermino(a, "")
    };
    this.armarUrlUbicaciones = function(a) {
        return ubicaciones.armarUrlUbicacionesConTermino(a, "")
    };
    this.armarUrlDirConTermino = function(a, c) {
        var b = ubicaciones.url_servicio_dir;
        a.filtro.url && (b =
            a.filtro.url);
        var b = b + ("/" + c),
            d = !1;
        a.filtro.extendido && (b += "/" + a.filtro.extendido, d = !0);
        a.filtro.sinIntersecciones && (b = d ? b + ("/" + a.filtro.sinIntersecciones) : b + ("/false/" + a.filtro.sinIntersecciones));
        return b
    };
    this.armarUrlLugaresSitioConTermino = function(a, c) {
        var b = ubicaciones.url_servicio_lugares;
        a.filtro.filtroclase && (a.filtro.url && (b = a.filtro.url), b = a.filtro.filtroclase.tipo ? b + ("/" + a.filtro.filtroclase.tipo) : b + "/0", b = a.filtro.filtroclase.subtipo ? b + ("/" + a.filtro.filtroclase.subtipo) : b + "/0", b += "/all/all/" +
            c.replace(RegExp(" ", "g"), "%2b"));
        return b + "&mode=native"
    };
    this.armarUrlLugaresConTermino = function(a, c) {
        var b = ubicaciones.url_servicio_lugares;
        a.filtro.filtroclase && (a.filtro.url && (b = a.filtro.url), b = a.filtro.filtroclase.tipo ? b + ("/" + a.filtro.filtroclase.tipo) : b + "/0", b = a.filtro.filtroclase.subtipo ? b + ("/" + a.filtro.filtroclase.subtipo) : b + "/0", b += "/" + c);
        a.filtro.extendido && (b += "/" + a.filtro.extendido);
        return b
    };
    this.armarUrlUbicacionesConTermino = function(a, c) {
        var b = ubicaciones.url_servicio_ubicaciones;
        a.filtro.filtroclase && (a.filtro.url && (b = a.filtro.url), b = a.filtro.filtroclase.tipo ? b + ("/" + a.filtro.filtroclase.tipo) : b + "/all", b = a.filtro.filtroclase.subtipo ? b + ("/" + a.filtro.filtroclase.subtipo) : b + "/all", b += "/" + c);
        a.filtro.extendido && (b += "/" + a.filtro.extendido);
        return b
    };
    this.seleccionarUbicacion = function(a) {
        var c = $(a.currentTarget).parent().attr("control-rel");
        $("#" + c).val($(a.currentTarget).html());
        $("#" + c + "-hidden").val($(a.currentTarget).attr("hidden-value"));
        $("#" + c.id + "-ul").css("display", "none")
    };
    this.procesarUbicacion = function(a, c) {
        $("#" + a.id + "-ul").empty();
        for (var b = 0; b < c.length; b++) {
            var d = JSON.stringify(c[b].geometry).replace(RegExp('"', "g"), "'");
            $("#" + a.id + "-ul").append('<li class="' + ubicaciones.suffix + '-li" hidden-value="' + d + '">' + c[b].properties.name + "</li>")
        }
        $("#" + a.id + "-ul li").click(ubicaciones.seleccionarUbicacion);
        $("#" + a.id + "-ul").css("display", "block")
    };
    this.obtenerDireccionDePunto = function(a, c, b, d, f) {
        a = ubicaciones.armarUrlDirPunto(a, c, b);
        $.ajax({
            type: "GET",
            url: a,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(a) {
                f ? f(a) : (a = a.calle.nombre.trim() + " " + a.altura + (a.bis ? " bis " : " ") + a.letra, d.val(a))
            },
            error: function(a) {
                console.log(a)
            }
        })
    };
    this.setStyleInLayer = function(a, c, b, d) {
        if (a)
            if ("string" == typeof a) $.ajax({
                type: "GET",
                url: a,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(a) {
                    var e = makeStyle(a),
                        f = null;
                    c.getLayers().forEach(function(a) {
                        a.get("id") == b && (f = a)
                    });
                    d ? f.setStyle(function(a, b) {
                        return ubicaciones.getStyleCluster(a, b, e)
                    }) : f.setStyle(e)
                },
                error: function(a) {
                    console.log(a)
                }
            });
            else {
                var f = makeStyle(a),
                    e = null;
                c.getLayers().forEach(function(a) {
                    a.get("id") == b && (e = a)
                });
                d ? e.setStyle(function(a, b) {
                    return ubicaciones.getStyleCluster(a, b, f)
                }) : e.setStyle(f)
            }
    };
    this.mostrarAtributos = "mostrarAtributos";
    this.addLayer = function(a, c, b, d, f, e, g, k) {
        var n = new XMLHttpRequest;
        n.open("GET", a, !0);
        n.onload = function() {
            var a = n.responseXML,
                q = ubicaciones.srsName;
            k && (q = k);
            q == ubicaciones.srsNameCRS84 && (a = n.response, a = a.replace(RegExp("EPSG:4326", "g"), q), a = jQuery.parseXML(a));
            q = c.readFeatures(a, {
                dataProjection: q,
                featureProjection: b.getView().getProjection().getCode()
            });
            a = ubicaciones.styleDraw;
            g instanceof ol.style.Style && (a = g);
            var p = new ol.source.Vector,
                m = {};
            m[ubicaciones.mostrarAtributos] = f;
            p.setProperties(m);
            a = new ol.layer.Vector({
                source: p,
                style: a,
                id: e
            });
            for (p = 0; p < q.length; p++) {
                var m = q[p],
                    r = m.getProperties();
                r[ubicaciones.atributoMapaId] = b.getTarget();
                r[ubicaciones.atributoTitulo] = d;
                m.setProperties(r)
            }
            a.getSource().addFeatures(q);
            b.addLayer(a);
            g instanceof ol.style.Style || ubicaciones.setStyleInLayer(g,
                b, e)
        };
        n.send()
    };
    this.centrarMapa = function(a, c) {
        var b = +new Date,
            d = ol.animation.pan({
                duration: 2E3,
                source: c.getView().getCenter(),
                start: b
            }),
            b = ol.animation.bounce({
                duration: 2E3,
                resolution: 4 * c.getView().getResolution(),
                start: b
            });
        c.beforeRender(d, b);
        c.getView().setCenter(a);
        c.getView().setZoom(15)
    };
    this.centrarMapaEnGeometry = function(a, c) {
        var b = +new Date;
        mensajeResultadoNoEncontrado;
        var d = ol.animation.pan({
                duration: 2E3,
                source: c.getView().getCenter(),
                start: b
            }),
            b = ol.animation.bounce({
                duration: 2E3,
                resolution: 1 *
                    c.getView().getResolution(),
                start: b
            });
        c.beforeRender(d, b);
        c.getView().fit(a, c.getSize(), {
            padding: [100, 100, 100, 100],
            constrainResolution: !1
        })
    };
    this.createRadio = function(a, c) {
        var b = document.createElement("input");
        b.setAttribute("type", "radio");
        b.setAttribute("name", a);
        b.setAttribute("value", c);
        return b
    };
    this.createLabel = function(a) {
        var c = document.createElement("label");
        c.innerHTML = a;
        return c
    };
    this.clearMap = function(a) {
        var c = null;
        a.getLayers().forEach(function(a) {
            "layerFeatures" == a.get("id") && (c = a)
        });
        c.getSource().clear();
        a.getLayers().forEach(function(a) {
            "layerDraw" == a.get("id") && (c = a)
        });
        c.getSource().clear()
    };
    this.createButtonClear = function(a, c) {
        var b = function() {
                var b = null;
                a.getLayers().forEach(function(a) {
                    "layerDraw" == a.get("id") && (b = a)
                });
                b.getSource().clear();
                c.val("")
            },
            d = document.createElement("button");
        d.addEventListener("click", function() {
            b();
            return !1
        }, !1);
        d.innerHTML = "x";
        d.title = "Borrar todo";
        d.type = "button";
        return d
    };
    this.agregarControlClear = function(a, c) {
        var b = document.createElement("div");
        b.className = ubicaciones.suffix + "-control-typedraw ol-control";
        var d = document.createElement("div"),
            f = ubicaciones.createButtonClear(a, c);
        d.appendChild(f);
        b.appendChild(d);
        ol.control.Control.call(this, {
            element: b
        })
    };
    this.atributoMapaId = "mapaId";
    this.atributoTitulo = "atributoTitulo";
    this.atributoTheGeom = "the_geom";
    this.atributoGeometry = "geometry";
    this.atributoBoundedBy = "boundedBy";
    this.atributoTituloDefault = "nombre";
    this.mostrarObjectoEnDivJQuery = function(a, c, b) {
        var d = "",
            f;
        for (f in c)
            if (f != ubicaciones.atributoMapaId &&
                f != ubicaciones.atributoTitulo && f != ubicaciones.atributoTheGeom && f != ubicaciones.atributoGeometry && f != ubicaciones.atributoBoundedBy && f != ubicaciones.mostrarAtributos) {
                var e = !0,
                    g = f;
                if (b && 0 < b.length)
                    for (var e = !1, k = 0; k < b.length; k++) {
                        var n = b[k];
                        n[f] && (e = !0, g = n[f])
                    }
                e && c[f] && (d += "<label class='" + ubicaciones.suffix + "-object-label'>" + g + ":</label>", d += "<span class='" + ubicaciones.suffix + "-object-dato'>" + c[f] + "</span>", d += "<br/>")
            }
        a.html(d)
    };
    this.cambiarControlDibujo = function(a, c, b, d, f, e) {
        var g = function(b) {
                var g =
                    null;
                a.getInteractions().forEach(function(a) {
                    "interactionDraw" == a.getProperties().id && (g = a)
                });
                a.removeInteraction(g);
                var k = null;
                a.getLayers().forEach(function(a) {
                    "layerDraw" == a.get("id") && (k = a)
                });
                k.setStyle(f);
                b = new ol.interaction.Draw({
                    features: d,
                    source: k.getSource(),
                    type: b
                });
                b.setProperties({
                    id: "interactionDraw"
                });
                b.on("drawend", function(a) {
                    e && "direccion" == e ? alert("Se debe mostrar la direcci\u00f3n") : ubicaciones.geojson.writeFeaturesInControl(a.feature, k.getSource(), c)
                });
                a.addInteraction(b)
            },
            k =
            ubicaciones.createLabel("Punto"),
            n = ubicaciones.createRadio("radioTypeDraw", "Point");
        n.setAttribute("checked", "checked");
        n.addEventListener("click", function() {
            g("Point")
        }, !1);
        var u = ubicaciones.createLabel("L\u00ednea"),
            q = ubicaciones.createRadio("radioTypeDraw", "LineString");
        q.addEventListener("click", function() {
            g("LineString")
        }, !1);
        var p = ubicaciones.createLabel("Pol\u00edgono"),
            m = ubicaciones.createRadio("radioTypeDraw", "Polygon");
        m.addEventListener("click", function() {
            g("Polygon")
        }, !1);
        b = document.createElement("div");
        b.className = ubicaciones.suffix + "-control-typedraw ol-control";
        var r = document.createElement("div");
        r.appendChild(n);
        r.appendChild(k);
        b.appendChild(r);
        k = document.createElement("div");
        k.appendChild(q);
        k.appendChild(u);
        b.appendChild(k);
        u = document.createElement("div");
        u.appendChild(m);
        u.appendChild(p);
        b.appendChild(u);
        p = document.createElement("div");
        m = ubicaciones.createButtonClear(a, c);
        p.appendChild(m);
        b.appendChild(p);
        ol.control.Control.call(this, {
            element: b
        })
    };
    "undefined" !== typeof ol && (ol.inherits(this.cambiarControlDibujo,
        ol.control.Control), ol.inherits(this.agregarControlClear, ol.control.Control));
    this.geojson = geojson;
    this.gml = gml;
    this.kml = kml
};

function ubicacionesControl() {
    this.controlHidden = this.controlVisible = null;
    this.estado = 0;
    this.mapa = null;
    this.getValue = function() {
        var a = this.controlHidden.val().replace(RegExp('"', "g"), '\\"').replace(RegExp("'", "g"), '"');
        return JSON.parse(a)
    };
    this.getClase = function() {
        var a = this.controlHidden.val().replace(RegExp('"', "g"), '\\"').replace(RegExp("'", "g"), '"'),
            a = JSON.parse(a);
        return "DIRRECCI\u00d3N" == a.properties.subtipo || "DIRECCI\u00d3N" == a.properties.subtipo || "CALLE" == a.properties.subtipo ? "direccion" :
            "lugar"
    };
    this.getLabel = function() {
        return this.controlVisible.val()
    };
    this.getMapa = function() {
        return this.mapa
    }
}

function BuscarDireccion() {
    this.calle = "";
    this.numero = null;
    this.bis = !1;
    this.letra = "";
    this.conInterseccion = !1;
    this.interseccion = "";
    this.getCalle = function() {
        return this.calle
    };
    this.setCalle = function(a) {
        this.calle = a
    };
    this.getNumero = function() {
        return this.numero
    };
    this.setNumero = function(a) {
        this.numero = a
    };
    this.isBis = function() {
        return this.bis
    };
    this.setBis = function(a) {
        this.bis = a
    };
    this.getLetra = function() {
        return this.letra
    };
    this.setLetra = function(a) {
        this.letra = a
    };
    this.toString = function() {
        return this.bis ?
            this.calle + " " + this.numero + " bis " + this.letra : this.calle + " " + this.numero + " " + this.letra
    };
    this.isConInterseccion = function() {
        return this.conInterseccion
    };
    this.setConInterseccion = function(a) {
        this.conInterseccion = a
    };
    this.getInterseccion = function() {
        return this.interseccion
    };
    this.setInterseccion = function(a) {
        this.interseccion = a
    }
}
$.fn.ubicaciones = function(a) {
    var c = new ubicacionesControl;
    c.controlVisible = $(this);
    var b = !0;
    a.sinBusqueda && 1 == a.sinBusqueda && (b = !1);
    var d = "",
        f = [];
    if (a.filtros)
        for (var e = 0; e < a.filtros.length; e++) {
            var g;
            "direccion" == a.filtros[e].filtro.clase ? g = ubicaciones.armarUrlDir(a.filtros[e]) : "lugar" == a.filtros[e].filtro.clase ? g = ubicaciones.armarUrlLugares(a.filtros[e]) : "ubicacion" == a.filtros[e].filtro.clase && (g = ubicaciones.armarUrlUbicaciones(a.filtros[e]));
            f.push(g)
        } else a.filtro ? "direccion" == a.filtro.clase ?
            d = ubicaciones.armarUrlDir(a) : "lugar" == a.filtro.clase ? d = ubicaciones.armarUrlLugares(a) : "ubicacion" == a.filtro.clase && (d = ubicaciones.armarUrlUbicaciones(a)) : d = ubicaciones.armarUrlDir(a);
    var k = ubicaciones.format_default;
    a.format && (k = a.format);
    var n = ubicaciones.styleDraw;
    a.pathImg && "undefined" != typeof ol && (n = new ol.style.Style({
        fill: new ol.style.Fill({
            color: "rgba(255, 255, 255, 0.3)"
        }),
        stroke: new ol.style.Stroke({
            color: "#E95F38",
            width: 2
        }),
        image: new ol.style.Icon({
            src: a.pathImg + "/boton-pin.png",
            scale: .6,
            offset: [0, 20]
        })
    }));
    if (b) {
        var u = function() {
                console.log("funci\u00f3n de procesamiento por defecto.")
            },
            b = [];
        if (a.filtros)
            for (e = 0; e < a.filtros.length; e++) b.push("direccion" == a.filtros[e].filtro.clase || "lugar" == a.filtros[e].filtro.clase || "ubicacion" == a.filtros[e].filtro.clase ? 0 == e ? ubicaciones.geojson.procesarUbicacion : ubicaciones.geojson.procesarUbicacionBase : ubicaciones.procesarUbicacion);
        else !a.filtro || "direccion" != a.filtro.clase && "lugar" != a.filtro.clase && "ubicacion" != a.filtro.clase || (u = "geojson" == k ?
            ubicaciones.geojson.procesarUbicacion : ubicaciones.procesarUbicacion);
        var q = 3;
        a.minLength && (q = a.minLength);
        var p = ubicaciones.mensajeResultadoNoEncontrado;
        a.mensajeResultadoNoEncontrado && (p = a.mensajeResultadoNoEncontrado);
        var m = 0,
            r, y = function(b, c) {
                40 == b.keyCode && $("#" + c.id + "-ul").find("li").first().addClass("focus").focus().removeClass("focus");
                clearTimeout(m);
                m = setTimeout(function() {
                    if ($(c).val().trim().length >= q) {
                        if (ubicaciones.validChar(b.keyCode)) {
                            var e = "#" + c.id + "-loading";
                            $(e).css("display", "inline");
                            "lugar" == a.filtro.clase ? d = ubicaciones.armarUrlLugaresConTermino(a, $(c).val()) : "ubicacion" == a.filtro.clase ? d = ubicaciones.armarUrlUbicacionesConTermino(a, $(c).val()) : "direccion" == a.filtro.clase && (d = ubicaciones.armarUrlDirConTermino(a, $(c).val()));
                            r && r.abort();
                            r = $.ajax({
                                type: "GET",
                                url: d,
                                contentType: "application/json; charset=utf-8",
                                dataType: "json",
                                data: {},
                                success: function(c) {
                                    var d = b.currentTarget;
                                    if ("direccion" == a.filtro.clase || "lugar" == a.filtro.clase || "ubicacion" == a.filtro.clase) {
                                        var f = !1;
                                        a.filtro.featureUnico &&
                                            (f = a.filtro.featureUnico);
                                        if (a.filtro.callback) {
                                            var g = a.filtro.callback;
                                            u(d, c, a.filtro.clase, f, a.filtro.featureUnico ? function(a) {
                                                ubicaciones.geojson.borrarYSeleccionarUbicacion(a, n);
                                                a = ubicaciones.parseJSON($(a.currentTarget).attr("hidden-value"));
                                                g(a)
                                            } : function(a) {
                                                ubicaciones.geojson.seleccionarUbicacion(a, n);
                                                a = ubicaciones.parseJSON($(a.currentTarget).attr("hidden-value"));
                                                g(a)
                                            })
                                        } else u(d, c, a.filtro.clase, f)
                                    }
                                    0 == $("#" + d.id + "-ul li").length && (c = '<li class="' + ubicaciones.suffix + '-li" label-value="" ' +
                                        ubicaciones.suffix + '-clase="' + a.filtro.clase + '" tabindex="-1"><span class="' + ubicaciones.suffix + '-li-preprincipal">' + p + "</span></li>", $("#" + d.id + "-ul").append(c));
                                    $(e).css("display", "none")
                                },
                                error: function(d, f) {
                                    if ("abort" != d.statusText) {
                                        var g = b.currentTarget,
                                            k = "",
                                            k = 0 === d.status ? "No se pudo conectar. Verifique conexi\u00f3n." : 404 == d.status ? "El recurso solicitado no se encontr\u00f3. Intente nuevamente." : 500 == d.status ? "Error interno del servidor. Intente nuevamente." : "timeout" === f ? "La solicitud ha expirado. Intente nuevamente." :
                                            "No se pudo conectar. Verifique conexi\u00f3n.",
                                            k = '<li class="' + ubicaciones.suffix + '-li" label-value="" ' + ubicaciones.suffix + '-clase="' + a.filtro.clase + '" tabindex="-1"><span class="' + ubicaciones.suffix + '-li-preprincipal">' + k + "</span></li>";
                                        $("#" + c.id + "-ul").empty();
                                        $("#" + g.id + "-ul").append(k);
                                        $("#" + g.id + "-ul").css("display", "block");
                                        $(e).css("display", "none")
                                    }
                                }
                            })
                        }
                    } else $("#" + c.id + "-ul").empty(), $("#" + c.id + "-ul").css("display", "none");
                    return c
                }, 500)
            };
        0 < f.length ? this.keyup(function(b) {
            var c = this;
            40 ==
                b.keyCode && $("#" + c.id + "-ul").find("li").first().addClass("focus").focus().removeClass("focus");
            clearTimeout(m);
            m = setTimeout(function() {
                if ($(c).val().trim().length >= q) {
                    if (ubicaciones.validChar(b.keyCode)) {
                        var e = "#" + c.id + "-loading";
                        $(e).css("display", "inline");
                        for (var g = [], k = ubicaciones.isDireccion($(c).val()), h = 0; h < f.length; h++) {
                            d = f[h];
                            "lugar" == a.filtros[h].filtro.clase ? d = ubicaciones.armarUrlLugaresConTermino(a.filtros[h], $(c).val()) : "ubicacion" == a.filtros[h].filtro.clase ? d = ubicaciones.armarUrlUbicacionesConTermino(a.filtros[h],
                                $(c).val()) : "direccion" == a.filtro.clase && (d = ubicaciones.armarUrlDirConTermino(a.filtros[h], $(c).val()));
                            if ("direccion" == a.filtros[h].filtro.clase)
                                if (a.filtros[h].filtro.callback) {
                                    var l = a.filtros[h].filtro.callback,
                                        m = function(a) {
                                            ubicaciones.geojson.seleccionarUbicacion(a, n);
                                            a = ubicaciones.parseJSON($(a.currentTarget).attr("hidden-value"));
                                            l(a)
                                        };
                                    g.push(function(a) {
                                        ubicaciones.geojson.procesarUbicacionClase(b.currentTarget, a, "direccion", k, m);
                                        $(e).css("display", "none")
                                    })
                                } else g.push(function(a) {
                                    ubicaciones.geojson.procesarUbicacionClase(b.currentTarget,
                                        a, "direccion", k);
                                    $(e).css("display", "none")
                                });
                            else "lugar" == a.filtros[h].filtro.clase ? a.filtros[h].filtro.callback ? (l = a.filtros[h].filtro.callback, m = function(a) {
                                ubicaciones.geojson.seleccionarUbicacion(a, n);
                                a = ubicaciones.parseJSON($(a.currentTarget).attr("hidden-value"));
                                l(a)
                            }, g.push(function(a) {
                                ubicaciones.geojson.procesarUbicacionClase(b.currentTarget, a, "lugar", !k, m);
                                $(e).css("display", "none")
                            })) : g.push(function(a) {
                                ubicaciones.geojson.procesarUbicacionClase(b.currentTarget, a, "lugar", !k);
                                $(e).css("display",
                                    "none")
                            }) : "ubicacion" == a.filtros[h].filtro.clase && (a.filtros[h].filtro.callback ? (l = a.filtros[h].filtro.callback, m = function(a) {
                                ubicaciones.geojson.seleccionarUbicacion(a, n);
                                a = ubicaciones.parseJSON($(a.currentTarget).attr("hidden-value"));
                                l(a)
                            }, g.push(function(a) {
                                ubicaciones.geojson.procesarUbicacionClase(b.currentTarget, a, "ubicacion", !k, m);
                                $(e).css("display", "none")
                            })) : g.push(function(a) {
                                ubicaciones.geojson.procesarUbicacionClase(b.currentTarget, a, "ubicacion", !k);
                                $(e).css("display", "none")
                            }));
                            $.ajax({
                                type: "GET",
                                url: d,
                                contentType: "application/json; charset=utf-8",
                                dataType: "json",
                                data: {},
                                success: g[h],
                                error: function(a) {
                                    $("#" + c.id + "-loading").css("display", "none");
                                    $(e).css("display", "none")
                                }
                            })
                        }
                    }
                } else $("#" + c.id + "-ul").empty(), $("#" + c.id + "-ul").css("display", "none");
                return c
            }, 300)
        }) : this.keyup(function(a) {
            y(a, this)
        });
        this.focusout(function(a) {
            $("." + ubicaciones.suffix + "-li:hover").length || $("." + ubicaciones.suffix + "-li.focus").length || $("#" + this.id + "-boton-search:hover").length || ("" == $("#" + this.id).val().trim() ?
                $("#" + this.id + "-hidden").val("") : (a = $("#" + this.id + "-hidden").val(), a = ubicaciones.parseJSON(a), null != a ? $("#" + this.id).val(a.properties.name) : $("#" + this.id).val("")), $("#" + this.id + "-ul").empty(), $("#" + this.id + "-ul").css("display", "none"))
        });
        $(document).on("keydown", function(a) {
            ubicaciones.isMoveKey(a.keyCode) && ($("." + ubicaciones.suffix + "-li:hover").length || $("." + ubicaciones.suffix + "-li:focus").length) && (a.preventDefault(), a.stopPropagation())
        });
        $(this).addClass(ubicaciones.suffix + "-input");
        $(this).css("padding-right",
            "18px");
        e = $(this).innerWidth();
        $(this).innerHeight();
        $(this).after("<input class='" + ubicaciones.suffix + "-hidden' id='" + this[0].id + "-hidden' type='hidden' value=''/>");
        c.controlHidden = $("#" + this[0].id + "-hidden");
        b = "";
        a.mapa && a.mapa.id && (b = a.mapa.id);
        g = !1;
        a.noReubicar && (g = a.noReubicar);
        g ? $(this).after("<ul class='" + ubicaciones.suffix + "-ul' id='" + this[0].id + "-ul' param-rel='" + JSON.stringify(a) + "' control-rel='" + this[0].id + "' mapa-rel='" + b + "' style='display:none;width:" + e + "px'></ul>") : $(this).after("<ul class='" +
            ubicaciones.suffix + "-ul' id='" + this[0].id + "-ul' param-rel='" + JSON.stringify(a) + "' control-rel='" + this[0].id + "' mapa-rel='" + b + "' style='display:none;position: absolute;width: 100%;'></ul>");
        $(this).wrap('<span class="ubicaciones-clearicon" />').after($("<span/>").click(function() {
            c.controlVisible.val("");
            c.controlVisible.focus();
            a.callback_clear && a.callback_clear()
        }));
        e = !0;
        a.sinImagenBusqueda && (e = !a.sinImagenBusqueda);
        e && (a.pathImg ? $(this).after("<img src='" + a.pathImg + "/loading.gif' class='" +
            ubicaciones.suffix + "-loading' id='" + this[0].id + "-loading' />") : $(this).after("<img src='img/loading.gif' class='" + ubicaciones.suffix + "-loading' id='" + this[0].id + "-loading' />"));
        e = !0;
        a.sinBotonBusqueda && (e = !a.sinBotonBusqueda);
        if (e) {
            $(this).after("<span class='" + ubicaciones.suffix + "-divider' id='" + this[0].id + "-divider'></span><button class='" + ubicaciones.suffix + "-boton-search' id='" + this[0].id + "-boton-search' />");
            var z = this[0];
            $("#" + this[0].id).css("border-right", "0px");
            $("#" + this[0].id + "-boton-search").click(function(a) {
                a.keyCode =
                    65;
                a.currentTarget = z;
                y(a, z);
                return !1
            })
        }
    }
    if (a.mapa && a.mapa.id) {
        e = ubicaciones.style_map_default;
        a.mapa.style && (e = a.mapa.style);
        $("#" + a.mapa.id).css(e);
        proj4.defs("EPSG:22185", "+proj=tmerc +lat_0=-90 +lon_0=-60 +k=1 +x_0=5500000 +y_0=0 +ellps=WGS84 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs");
        proj4.defs("CRS:84", "+proj=longlat +ellps=WGS84 +datum=WGS84 +no_defs +axis=enu");
        var e = [5408191, 6328836, 5460856, 6382958],
            l = new ol.Map({
                target: a.mapa.id,
                layers: [new ol.layer.Tile({
                    source: new ol.source.TileWMS({
                        url: "http://infomapa.rosario.gov.ar/wms/planobase?",
                        params: {
                            VERSION: "1.1.1",
                            LAYERS: "distritos_descentralizados,rural_metropolitana,manzanas_metropolitana,limites_metropolitana,limite_municipio,sin_manzanas,manzanas,manzanas_no_regularizada,espacios_verdes,canteros,av_circunvalacion,avenidas_y_boulevares,sentidos_de_calle,via_ferroviaria,puentes,hidrografia,islas_del_parana,bancos_de_arena,autopistas,nombres_de_calles,numeracion_de_calles",
                            format: "image/jpeg",
                            map_imagetype: "jpeg"
                        },
                        attributions: [new ol.Attribution({
                            html: "\u00a9 Municipalidad de Rosario"
                        })]
                    })
                })],
                scales: [500, 1E3, 2E3, 4E3, 1E4, 25E3, 5E4, 75E3, 1E5, 15E4, 2E5],
                view: new ol.View({
                    projection: "EPSG:22185",
                    units: "m",
                    center: ol.extent.getCenter(e),
                    extent: e,
                    zoom: 12
                })
            });
        c.mapa = l;
        e = new ol.source.Vector({
            features: []
        });
        a.mapa.clustering ? (e = new ol.source.Cluster({
                distance: 1,
                source: e
            }), e = new ol.layer.AnimatedCluster({
                id: "layerFeatures",
                source: e,
                animationDuration: 700,
                style: ubicaciones.getStyleCluster
            }), b = new ol.interaction.SelectCluster({
                pointRadius: 10,
                animate: !0,
                featureStyle: ubicaciones.styleInteractionCluster
            }),
            l.addInteraction(b)) : e = new ol.layer.Vector({
            source: e,
            style: n,
            id: "layerFeatures"
        });
        l.addLayer(e);
        a.mapa && a.mapa.style_features && ubicaciones.setStyleInLayer(a.mapa.style_features, l, "layerFeatures", a.mapa.clustering);
        c.mapa = l;
        if (a.filtro && a.filtro.cargarAlInicio) {
            var h;
            "lugar" == a.filtro.clase ? d = ubicaciones.armarUrlLugaresConTermino(a, $(this).val()) : h = a.filtro.extendido ? {
                term: $(this).val(),
                extendido: !0
            } : {
                term: $(this).val()
            };
            $.ajax({
                type: "GET",
                url: d,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                data: h,
                success: function(b) {
                    if ("direccion" == a.filtro.clase || "lugar" == a.filtro.clase) {
                        var c = function() {
                            console.log("funci\u00f3n de dibujo en mapa por defecto.")
                        };
                        "geojson" == k && (c = ubicaciones.geojson.dibujarEnMapa);
                        c(b, l, null, a.mapa.clustering)
                    }
                },
                error: function(a) {
                    console.log(a)
                }
            })
        }
        if (a.mapa.features)
            if (h = "<div class='" + ubicaciones.suffix + "-leftpanel' id='" + this[0].id + "-leftpanel'>", h += "<div class='" + ubicaciones.suffix + "-leftpanel-title'><span class='" + ubicaciones.suffix + "-leftpanel-title-back' onclick='$(\"#" +
                a.mapa.id + '").find(".' + ubicaciones.suffix + '-leftpanel").animate({"left":"-500"},500);' + ubicaciones.suffix + '.deselectAllFeatures("' + a.mapa.id + "\");'></span><span class='" + ubicaciones.suffix + "-leftpanel-title-text'></span></div>", h += "<div class='" + ubicaciones.suffix + "-leftpanel-content'></div>", h += "</div>", $("#" + a.mapa.id).prepend(h), e = $("#" + a.mapa.id).innerWidth(), h = $("#" + a.mapa.id).innerHeight(), e *= .3, $("#" + this[0].id + "-leftpanel").css("width", e), $("#" + this[0].id + "-leftpanel").css("left", -e), $("#" +
                    this[0].id + "-leftpanel").css("height", h), a.mapa.clustering || (h = new ol.interaction.Select({
                    condition: ol.events.condition.click,
                    filter: function(a, b) {
                        var c = b.getStyle(),
                            c = ubicaciones.cloneObject(c),
                            c = ubicaciones.cambiarStyleEnSelect(c);
                        a.setStyle(c);
                        c = b.getSource().getProperties();
                        a.setProperties(c);
                        return !0
                    }
                }), l.addInteraction(h), h.on("select", function(b) {
                    b.deselected.forEach(function(a) {
                        a.setStyle(null)
                    });
                    if (b.selected[0]) {
                        b = b.selected[0].getProperties();
                        var c = b[ubicaciones.atributoMapaId],
                            d = b[b[ubicaciones.atributoTitulo]];
                        d && ($("#" + c).find("." + ubicaciones.suffix + "-leftpanel").find("." + ubicaciones.suffix + "-leftpanel-title-text").html(d), ubicaciones.mostrarObjectoEnDivJQuery($("#" + c).find("." + ubicaciones.suffix + "-leftpanel").find("." + ubicaciones.suffix + "-leftpanel-content"), b, b[ubicaciones.mostrarAtributos]), $("#" + c).find("." + ubicaciones.suffix + "-leftpanel").animate({
                            left: "0"
                        }, 500))
                    } else $("#" + a.mapa.id).find("." + ubicaciones.suffix + "-leftpanel").animate({
                        left: "-500"
                    }, 500)
                })), Array.isArray(a.mapa.features))
                for (h = 0; h <
                    a.mapa.features.length; h++) {
                    if (b = k, a.mapa.features[h].format && (b = a.mapa.features[h].format), a.mapa.features[h].url) {
                        e = function(a, b, c) {
                            console.log("funci\u00f3n para agregar una capa de features al mapa.")
                        };
                        "geojson" == b ? e = ubicaciones.geojson.addLayer : "gml" == b ? e = ubicaciones.gml.addLayer : "kml" == b && (e = ubicaciones.kml.addLayer);
                        b = n;
                        a.mapa.features[h].style && (b = JSON.parse(JSON.stringify(a.mapa.features[h].style)));
                        g = ubicaciones.atributoTituloDefault;
                        a.mapa.features[h].atributoTitulo && (g = a.mapa.features[h].atributoTitulo);
                        var A = [];
                        a.mapa.features[h].mostrarAtributos && (A = a.mapa.features[h].mostrarAtributos);
                        e(a.mapa.features[h].url, l, g, A, a.mapa.features[h].id, b, a.mapa.features[h].srsName)
                    }
                } else h = function() {
                    console.log("funci\u00f3n de dibujo en mapa por defecto.")
                }, "geojson" == k && (h = ubicaciones.geojson.dibujarEnMapa), h(a.mapa.features, l, null, a.mapa.clustering);
        if (a.mapa.dibujar) {
            var v = new ol.source.Vector({
                wrapX: !1
            });
            g = "";
            e = h = !1;
            "punto" == a.mapa.dibujar ? (g = "Point", e = !0) : "l\u00ednea" == a.mapa.dibujar || "linea" == a.mapa.dibujar ?
                (g = "LineString", e = !0) : "poligono" == a.mapa.dibujar ? (g = "Polygon", e = !0) : "todos" == a.mapa.dibujar && (g = "Point", h = !0);
            if ("" != g) {
                b = new ol.layer.Vector({
                    source: v,
                    style: n,
                    id: "layerDraw"
                });
                l.addLayer(b);
                a.mapa && a.mapa.style_features && ubicaciones.setStyleInLayer(a.mapa.style_features, l, "layerDraw", a.mapa.clustering);
                b = new ol.Collection;
                g = new ol.interaction.Draw({
                    features: b,
                    source: v,
                    type: g
                });
                g.setProperties({
                    id: "interactionDraw"
                });
                var t = $(this),
                    w = [],
                    x = !1;
                a.mapa.featureUnico && (x = a.mapa.featureUnico);
                g.on("drawend",
                    function(b) {
                        a.mapa.mostrar && "direccion" == a.mapa.mostrar ? (x && ubicaciones.clearMap(l), b = b.feature.getGeometry().getCoordinates(), ubicaciones.obtenerDireccionDePunto(a, b[0], b[1], t, a.mapa.callback_punto ? function(b) {
                            t.val(b.calle.nombre.trim() + " " + b.altura + (b.bis ? " bis " : " ") + b.letra);
                            a.mapa.callback_punto(b)
                        } : function(a) {
                            t.val(a.calle.nombre.trim() + " " + a.altura + (a.bis ? " bis " : " ") + a.letra)
                        })) : w = ubicaciones.geojson.writeFeaturesInControl(b.feature, v, t)
                    });
                l.addInteraction(g);
                x || (g = new ol.interaction.Modify({
                    features: b,
                    deleteCondition: function(a) {
                        return ol.events.condition.shiftKeyOnly(a) && ol.events.condition.singleClick(a)
                    }
                }), g.on("modifyend", function(b) {
                    if (a.mapa.mostrar && "direccion" == a.mapa.mostrar) {
                        var c;
                        v.getFeatures().forEach(function(a) {
                            c = a
                        });
                        c && (b = c.getGeometry().getCoordinates(), ubicaciones.obtenerDireccionDePunto(a, b[0], b[1], t, a.mapa.callback_punto ? function(b) {
                            t.val(b.calle.nombre.trim() + " " + b.altura + (b.bis ? " bis " : " ") + b.letra);
                            a.mapa.callback_punto(b)
                        } : function(a) {
                            t.val(a.calle.nombre.trim() + " " + a.altura +
                                (a.bis ? " bis " : " ") + a.letra)
                        }))
                    } else w = ubicaciones.geojson.writeFeaturesInControl(b.feature, v, t)
                }), l.addInteraction(g));
                h ? a.mapa.mostrar ? l.getControls().extend([new ubicaciones.cambiarControlDibujo(l, t, w, b, n, a.mapa.mostrar)]) : l.getControls().extend([new ubicaciones.cambiarControlDibujo(l, t, w, b, n)]) : e && l.getControls().extend([new ubicaciones.agregarControlClear(l, t)])
            }
        }
        $("#" + a.mapa.id).data("map", l)
    }
    this.focusin(function(a) {
        $(a.target).select()
    });
    return c
};
jQuery.fn.extend({
    resaltarUl: function(a, c) {
        var b = a.split(" "),
            b = b.sort(function(a, b) {
                return b.length - a.length
            });
        for (i = 0; i < b.length; i++) {
            var d = new RegExp("(<[^>]*>)|(" + b[i].replace(/([-.*+?^${}()|[\]\/\\])/g, "\\$1") + ")", "ig");
            $(this).find("li").each(function() {
                var a = $(this).find("." + ubicaciones.suffix + "-li-principal").html();
                if (a) var b = a.replace(d, function(a, b, d) {
                    return "<" == a.charAt(0) ? a : '<span class="' + c + '">' + d + "</span>"
                });
                $(this).find("." + ubicaciones.suffix + "-li-principal").html(b)
            })
        }
    }
});
L.drawLocal = {
    draw: {
        toolbar: {
            actions: {
                title: 'Cancelar el dibujo',//'Cancel drawing',
                text: 'Cancelar'//'Cancel'
            },
            undo: {
                title: 'Eliminar el ultimo punto',//'Delete last point drawn',
                text: 'Eliminar el ultimo punto'//'Delete last point'
            },
            buttons: {
                polyline: 'Dibujar una linea',//'Draw a polyline',
                polygon: 'Dibujar un poligono',//'Draw a polygon',
                rectangle: 'Dibujar un rectangulo',//'Draw a rectangle',
                circle: 'Dibujar un circulo',//'Draw a circle',
                marker: 'Dibujar un caso puntual'//'Draw a marker'
            },
            finish: {
                title: 'Finalizar el dibujo', //'Cancel drawing',
                text: 'Finalizar'//'Cancel'
            }
        },
        handlers: {
            circle: {
                tooltip: {
                    start: 'Click y arrastre para dibujar un circulo'//'Click and drag to draw circle.'
                },
                radius: 'Radio'//'Radius'
            },
            marker: {
                tooltip: {
                    start: 'Click en el mapa para un caso puntual'//'Click map to place marker.'
                }
            },
            polygon: {
                tooltip: {
                    start: 'Click para comenzar a dibujar',//'Click to start drawing shape.',
                    cont: 'Click para continuar dibujando',//'Click to continue drawing shape.',
                    end: 'Click en el primer punto para cerrar'//'Click first point to close this shape.'
                }
            },
            polyline: {
                error: '<strong>Error:</strong> Las lineas no se deben cruzar!',
                tooltip: {
                    start: 'Click para comenzar a dibujar',//'Click to start drawing line.',
                    cont: 'Click para continuar dibujando',//'Click to continue drawing line.',
                    end: 'Click en el ultimo punto para terminar'//'Click last point to finish line.'
                }
            },
            rectangle: {
                tooltip: {
                    start: 'Click y arrastre para dibujar un rectangulo'//'Click and drag to draw rectangle.'
                }
            },
            simpleshape: {
                tooltip: {
                    end: 'Suelte el raton para terminar de dibujar'//'Release mouse to finish drawing.'
                }
            }
        }
    },
    edit: {
        toolbar: {
            actions: {
                save: {
                    title: 'Guardar cambios',//'Save changes.',
                    text: 'Guardar'//'Save'
                },
                cancel: {
                    title: 'Cancelar la edición y descartar todos los cambios',//'Cancel editing, discards all changes.',
                    text: 'Cancelar'//'Cancel'
                }
            },
            buttons: {
                edit: 'Editar capa',//'Edit layers.',
                editDisabled: 'No hay capas para editar',//'No layers to edit.',
                remove: 'Eliminar capa',//'Delete layers.',
                removeDisabled: 'No hay capas para eliminar'//'No layers to delete.'
            }
        },
        handlers: {
            edit: {
                tooltip: {
                    text: 'Arrastre para editar',//'Drag handles, or marker to edit feature.',
                    subtext: 'Click en cancelar para descargar los cambios'//'Click cancel to undo changes.'
                }
            },
            remove: {
                tooltip: {
                    text: 'Click para eliminar'//'Click on a feature to remove'
                }
            }
        }
    }
};

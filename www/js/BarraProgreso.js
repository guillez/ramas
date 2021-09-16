/**
 * Esta clase es la encargada de manejar el componente visual de la barra de progreso.
 */
class BarraProgreso {

    /**
     * Constructor del componente visual barra de progreso.
     * @param contenedor Elemento del DOM donde se incluye la barra de progreso.
     * @param color El color (hexadecimal) que tendrá la barra de progreso.
     */
    constructor(contenedor, color) {
        let _contenedor;
        let _color;
        let _bar;

        this._contenedor = contenedor;
        this._color = color;
        this._bar = new ProgressBar.Line(this._contenedor, {
            strokeWidth: 4,
            color: this._color,
            trailColor: '#f4f4f4',
            trailWidth: 2,
            easing: 'easeInOut',
            duration: 800,
            svgStyle: null,
            text: {
                value: '',
                alignToBottom: false,
                style: {
                    // Text color.
                    // Default: same as stroke color (options.color)
                    color: '#f00',
                    position: 'absolute',
                    left: '50%',
                    top: '25px',
                    padding: 0,
                    margin: 0,
                    // You can specify styles which will be browser prefixed
                    transform: {
                        prefix: true,
                        value: 'translate(-50%, -50%)'
                    }
                }
            },
            from: {color: this._color},
            to: {color: this._color},
            // Set default step function for all animate calls
            step: (state, bar) => {
                bar.path.setAttribute('stroke', state.color);
                var value = Math.round(bar.value() * 100);
                if (value === 0) {
                    bar.setText('');
                } else {
                    bar.setText(value + '%');
                }

                bar.text.style.color = state.color;
            }
        });
        this._bar .text.style.fontFamily = '"Raleway", Helvetica, sans-serif';
        this._bar .text.style.fontSize = '1.5rem';
    }

    /**
     * Asigna el progreso a la barra, disparando la animación que
     * modifica el nivel de la misma. Es importante tener en cuenta
     * que la barra de progreso se maneja con números enteros que son
     * redondeados al momento de calcular valor = progreso / 100.0.
     * @param progreso Número entero.
     */
    asignarProgreso(progreso) {
        this._bar.animate( progreso / 100.0 );
    }
}
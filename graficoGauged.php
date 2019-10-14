
<script>
        var chartliexample3 = echarts.init(document.getElementById('chartli3'));
        chartliexample3option = {
            /*backgroundColor: '#F0E6EF',*/

            tooltip: {
                formatter: "{a} <br />{b} : {c}%"
            },
            toolbox: {
                show: false,
                feature: {
                    mark: { show: false },
                    restore: { show: true, title: 'Actualizar' },
                    saveAsImage: { show: true, title: 'Guardar como imagen' }
                }
            },
            series: [
                {
                    name: 'Indicadores Proyecto',
                    type: 'gauge',
                    startAngle: 180,
                    endAngle: 0,
                    center: ['50%', '85%'],    // Default global center
                    radius: 100,
                    axisLine: {            // Coordinate axis
                        lineStyle: {       // Property lineStyle control line style
                            width: 40
                        }
                    },
                    itemStyle: {
                        normal: {
                            color: '#0EAD69',
                            width: 10
                        }
                    },
                    axisTick: {            // Axis markers
                        splitNumber: 4,   // How much of each split segment segment
                        length: 10,        // Attribute length Control line length
                    },
                    axisLabel: {           // Axis text labels
                    	show: false,
                        formatter: function (v) {
                            switch (v + '') {
                                case '10': return 'Bajo';
                                case '50': return 'Medio';
                                case '90': return 'Alto';
                                default: return '';
                            }
                        },
                        textStyle: {       // The remaining properties using the global default text style
                            color: '#000',
                            fontSize: 10,
                            fontWeight: 'bolder'
                        }
                    },
                    pointer: {
                        width: 8,
                        length: '75%',
                        color: 'rgba(255, 255, 255, 0.8)'
                    },
                    title: {
                        show: false,
                        offsetCenter: [0, '-60%'],       // x, y, units px
                        textStyle: {       // The remaining properties using the global default text style
                            color: '#fff',
                            fontSize: 30
                        }
                    },
                    detail: {
                        show: false,
                        backgroundColor: 'rgba(0,0,0,0)',
                        borderWidth: 0,
                        borderColor: '#ccc',
                        width: 100,
                        height: 40,
                        offsetCenter: [0, -40],       // x, y, units px
                        formatter: '{value}%',
                        textStyle: {       // The remaining properties using the global default text style
                            fontSize: 30
                        }
                    },
                    data: [{ value: 20, name: 'Consumo Dinero' }]
                }
            ]
        };

     
       
		chartliexample3option.series[0].data[0].value = <?php echo $porcentajeConsumido;?>;
        chartliexample3.setOption(chartliexample3option, true);
        
		
    </script>
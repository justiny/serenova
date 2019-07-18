function nelioabStringifyTimestamp( timestamp ) {

	var d = new Date( timestamp );
	var year = d.getFullYear();
	var month = d.getMonth() + 1;
	var day = d.getDate();

	if ( 10 > month ) {
		month = '0' + month;
	}//end if

	if ( 10 > day ) {
		day = '0' + day;
	}//end if

	return year + '-' + month + '-' + day;

}//end nelioabStringifyTimestamp()

function nelioabLightenColor( col ) {
	var amt = 30;
	var usePound = false;
	if ( col[0] == "#" ) {
		col = col.slice(1);
		usePound = true;
	}

	var num = parseInt(col,16);

	var r = (num >> 16) + amt;

	if ( r > 255 ) r = 255;
	else if  (r < 0) r = 0;

	var b = ((num >> 8) & 0x00FF) + amt;

	if ( b > 255 ) b = 255;
	else if  (b < 0) b = 0;

	var g = (num & 0x0000FF) + amt;

	if ( g > 255 ) g = 255;
	else if  ( g < 0 ) g = 0;

	return (usePound?"#":"") + (g | (b << 8) | (r << 16)).toString(16);
}//end nelioabLightenColor()

/**
 *
 */
if (!String.prototype.nelioabformat) {
	String.prototype.nelioabformat = function() {
		var args = arguments;
		return this.replace(/{(\d+)}/g, function(match, number) {
			return typeof args[number] != 'undefined' ? args[number] : match;
		});
	};
}

/**
 *
 * labels:
 *	title		=>
 *	subtitle =>
 *	xaxis		=>
 *	yaxis		=>
 * column	=>
 * detail	=>
 *
 */
function makeConversionRateGraphic(ctx, labels, categories, data) {

	var values = [], colors = [];
	for ( var i = 0; i < data.length; ++i ) {
		values.push( data[ i ].y );
		colors.push( data[ i ].color );
	}//end for

	return new Chart( ctx, {
		type: 'bar',
		data: {
			datasets: [{
				data: values,
				backgroundColor: colors
			}],
			labels: categories
		},
		options: {
			layout: {
				padding: {
					left: 10,
					right: 0,
					top: 0,
					bottom: 0
				}
			},
			tooltips: {
				callbacks: {
					label: function( tooltipItem, data ) {
						return labels['column'].nelioabformat(tooltipItem.yLabel);
					}
				}
			},
			responsive: true,
			maintainAspectRatio: false,
			title: {
				display: true,
				text: labels.title,
				fontSize: 18,
				fontColor: '#464646',
				fontStyle: 'normal',
				fontFamily: "'Open Sans', sans-serif"
			},
			legend: {
				display: false
			},
			scales: {
				yAxes: [{
					scaleLabel: {
						display: true,
						labelString: labels.yaxis
					},
					ticks: {
						min: 0,
						max: 100
					}
				}]
			}
		}
	});

}//end makeConversionRateGraphic()

/**
 *
 * labels:
 *	title		=>
 *	subtitle =>
 *	xaxis		=>
 *	yaxis		=>
 * column	=>
 * detail	=>
 *
 */
function makeImprovementFactorGraphic(ctx, labels, categories, data) {

	var values = [], colors = [];
	for ( var i = 0; i < data.length; ++i ) {
		values.push( data[ i ].y );
		colors.push( data[ i ].color );
	}//end for

	var min = 0, max = 0;
	for ( var i = 0; i < values.length; ++i ) {
		if ( values[ i ] < min ) {
			min = values[ i ];
		}//end if
		if ( values[ i ] > max ) {
			max = values[ i ];
		}//end if
	}//end for

	if ( min >= 0 && max < 100 ) {
		min = 0;
		max = 100;
	}//end if

	if ( min > -100 && max <= 0 ) {
		min = -100;
		max = 0;
	}//end if

	if ( min < 0 ) { min = Math.floor( min ); }
	if ( max < 0 ) { max = Math.floor( max ); }
	if ( min > 0 ) { min = Math.ceil( min ); }
	if ( max > 0 ) { max = Math.ceil( max ); }

	return new Chart( ctx, {
		type: 'bar',
		data: {
			datasets: [{
				data: values,
				backgroundColor: colors
			}],
			labels: categories
		},
		options: {
			layout: {
				padding: {
					left: 10,
					right: 0,
					top: 0,
					bottom: 0
				}
			},
			tooltips: {
				callbacks: {
					label: function( tooltipItem, data ) {
						return labels['detail'].nelioabformat( values[ tooltipItem.index ] );
					}
				}
			},
			responsive: true,
			maintainAspectRatio: false,
			title: {
				display: true,
				text: labels['title'],
				fontSize: 18,
				fontColor: '#464646',
				fontStyle: 'normal',
				fontFamily: "'Open Sans', sans-serif"
			},
			legend: {
				display: false
			},
			scales: {
				yAxes: [{
					scaleLabel: {
						display: true,
						labelString: labels.yaxis
					},
					ticks: {
						min: min,
						max: max
					}
				}]
			}
		}
	});

}//end makeImprovementFactorGraphic()


function makeTimelinePerAlternativeGraphic( ctx, labels, alternatives, startingDate, max ) {

	var series = [];
	var colors = ['#00b193', '#13b5ea', '#ffd200', '#f47b20', '#00958f', '#a0d5b5', '#f05133',
	'#5d87a1', '#afbd22', '#e31b23', '#83cfca', '#532e63', '#215352', '#00467f', '#bec0c2'];

	series.push( {
		label: labels['original'],
		backgroundColor: '#CC0000',
		borderColor: '#CC0000',
		fill: false,
		data: alternatives[0]
	} );

	var j=0; var i;
	for ( i=1; i<alternatives.length; ++i ) {
		series.push( {
			label: labels['alternative'].replace('%s', i),
			backgroundColor: colors[ j ],
			borderColor: colors[ j ],
			fill: false,
			data: alternatives[ i ]
		} );
		++j;
		if (j > colors.length) j = 0;
	}

	for ( i=0; i<series.length; ++i ) {
		var data = series[ i ].data || [];
		for ( j=0; j<data.length; ++j ) {
			data[ j ] = {
				y: data[ j ],
				x: nelioabStringifyTimestamp( startingDate + ( 24 * 3600 * 1000 * j ) )
			};
		}
	}

	return new Chart( ctx, {
		type: 'line',
		data: {
			datasets: series
		},
		options: {
			tooltips: {
				callbacks: {
					label: function( tooltipItem, data ) {
						return data.datasets[ tooltipItem.datasetIndex ].label + ': ' + tooltipItem.yLabel + '%';
					}
				}
			},
			layout: {
				padding: {
					left: 10,
					right: 0,
					top: 0,
					bottom: 50
				}
			},
			responsive: true,
			maintainAspectRatio: false,
			title: {
				display: true,
				text: labels['title'],
				fontSize: 18,
				fontColor: '#464646',
				fontStyle: 'normal',
				fontFamily: "'Open Sans', sans-serif"
			},
			legend: {
				labels: {
					usePointStyle: true,
					fontStyle: 'bold',
					fontColor: '#333333',
					fontSize: 12
				},
				position: 'bottom',
			},
			scales: {
				xAxes: [{
					type: 'time',
					ticks: {
						autoSkip: true,
						autoSkipPadding: 10
					},
					time: {
						unit: 'day',
						displayFormats: {
							day: 'D. MMM'
						}
					}
				}],
				yAxes: [{
					scaleLabel: {
						display: true,
						labelString: labels.yaxis
					},
					ticks: {
						min: 0,
						max: max
					}
				}]
			}
		}
	});

}//end makeTimelinePerAlternativeGraphic()

function drawGraphic( ctx, data, label, baseColor ) {

	if ( ! baseColor ) {
		baseColor = '#CCCCCC';
	}//end if

	var values = [], labels = [];
	for ( var i = 0; i < data.length; ++i ) {
		values.push( data[ i ].y );
		labels.push( data[ i ].name );
	}//end if

	var colors = [];
	var color = baseColor;
	for ( i = 0; i < values.length; ++i ) {
		if ( 0 === i % 4 ) {
			color = baseColor;
		}//end if
		colors.push( color );
		color = nelioabLightenColor( color );
	}//end for

	return new Chart( ctx, {
		type: 'pie',
		data: {
			datasets: [{
				data: values,
				backgroundColor: colors
			}],
			labels: labels
		},
		options: {
			tooltips: {
				callbacks: {
					label: function( tooltipItem ) {
						var index = tooltipItem.index;
						if ( 0.1 === values[ index ] ) {
							return labels[ index ] + ': 0';
						} else {
							return labels[ index ] + ': ' + values[ index ];
						}//end if
					}
				}
			},
			legend: {
				display: false
			},
			layout: {
				padding: {
					left: 10,
					right: 10,
					top: 0,
					bottom: 0
				}
			},
			responsive: true,
			maintainAspectRatio: false
		}
	});

}//end drawGraphic()

function drawAlternativeGraphic( ctx, portionValue, portionLabel, color, totalValue, totalLabel ) {

	if ( ! color ) {
		color = '#cccccc';
	}//end if

	if ( portionValue > totalValue ) {
		totalValue = portionValue;
	}//end if

	totalValue = totalValue - portionValue;

	if ( ! totalValue && ! portionValue ) {
		totalValue = 0.1;
	}//end if

	return new Chart( ctx, {
		type: 'pie',
		data: {
			datasets: [{
				data: [ portionValue, totalValue ],
				backgroundColor: [ color, nelioabLightenColor( color ) ]
			}]
		},
		labels: [
			totalLabel,
			portionLabel
		],
		options: {
			tooltips: {
				callbacks: {
					label: function( tooltipItem, data ) {
						if ( 0 === tooltipItem.index ) {
							return portionLabel + ': ' + portionValue;
						} else {
							if ( 0.1 !== totalValue ) {
								return totalLabel + ': ' + ( totalValue + portionValue );
							} else {
								return totalLabel + ': 0';
							}//end if
						}//end if
					}
				}
			},
			legend: {
				display: false
			},
			layout: {
				padding: {
					left: 10,
					right: 10,
					top: 0,
					bottom: 0
				}
			},
			responsive: true,
			maintainAspectRatio: false
		}
	});

}//end drawAlternativeGraphic()

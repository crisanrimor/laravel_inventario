import { formatCurrency } from "../Utils/currency"

export function useDashboardCharts() {
    const chart1 = {
        legend: {
            show: false,
            position: "top",
            horizontalAlign: "left",
        },
        colors: ["#465FFF", "#9CB9FF"],
        chart: {
            fontFamily: "Outfit, sans-serif",
            foreColor: '#9CA3AF',
            toolbar: {
                show: false,
            },
        },
        fill: {
            gradient: {
                enabled: true,
                opacityFrom: 0.55,
                opacityTo: 0,
            },
        },
        stroke: {
            curve: "straight",
            width: [2, 2],
        },
        markers: {
            size: 0,
        },
        grid: {
            xaxis: {
                lines: { show: false },
            },
            yaxis: {
                lines: { show: true },
            },
        },
        dataLabels: {
            enabled: false,
        },
        tooltip: {
            y: {
                formatter: (value, { seriesIndex }) => {
                if (seriesIndex !== 0) {
                    return formatCurrency(value)
                }
                    return value
                }
            }
        },
        xaxis: {
            type: "category",
            categories: [
                "Jan","Feb","Mar","Apr","May","Jun",
                "Jul","Aug","Sep","Oct","Nov","Dec",
            ],
            axisBorder: { show: false },
            axisTicks: { show: false },
            tooltip: false,
        },
        yaxis: {
            labels: {
                formatter: (value) => formatCurrency(value)
            },
            title: {
                style: {
                    fontSize: "0px",
                },
            },
        }
    }

    const chart2 = {
        colors: ["#465FFF", "#9CB9FF"],
        chart: {
            fontFamily: "Outfit, sans-serif",
            foreColor: '#9CA3AF',
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 5,
                borderRadiusApplication: 'end'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: [
            "Jan","Feb","Mar","Apr","May","Jun",
            "Jul","Aug","Sep","Oct","Nov","Dec",
            ],
        },
        yaxis: {
            title: {
                text: 'Cantidad Productos'
            }
        },
        fill: {
            opacity: 1
        }
    }

    return { chart1, chart2 }
}

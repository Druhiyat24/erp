<?php
include '../../include/conn.php';
?><script>
    $('table tbody tr').on('click', 'td:eq(0)', function() {
        var kode_rak = $(this).closest('tr').find('td:eq(0)').attr('value');
        $('#chart_dashboard1').empty()
        $.ajax({
            type: 'POST',
            url: 'get_percentase.php',
            data: {
                'kode_rak': kode_rak
            },
            success: function(response) {
                var data2 = response;
                console.log(data2);


                // var data2 = '50';
                var options = {
                    chart: {
                        height: 250,
                        type: "radialBar",
                    },

                    series: [data2],
                    colors: ["#20E647"],
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                margin: 0,
                                size: "70%",
                                background: "#293450"
                            },
                            track: {
                                dropShadow: {
                                    enabled: true,
                                    top: 2,
                                    left: 0,
                                    blur: 4,
                                    opacity: 0.15
                                }
                            },
                            dataLabels: {
                                name: {
                                    offsetY: -10,
                                    color: "#fff",
                                    fontSize: "13px"
                                },
                                value: {
                                    color: "#fff",
                                    fontSize: "30px",
                                    show: true
                                }
                            }
                        }
                    },
                    fill: {
                        type: "gradient",
                        gradient: {
                            shade: "dark",
                            type: "vertical",
                            gradientToColors: ["#87D4F9"],
                            stops: [0, 100]
                        }
                    },
                    stroke: {
                        lineCap: "round"
                    },
                    labels: ["Digunakan"]
                };

                var chart = new ApexCharts(document.querySelector("#chart_dashboard1"), options);

                chart.render();
            }
        });
    });
</script>

<script>
    $('table tbody tr').on('click', 'td:eq(0)', function() {
        var kode_rak = $(this).closest('tr').find('td:eq(0)').attr('value');
        $('#div_dashboard2').empty()
        $.ajax({
            type: 'POST',
            url: 'get_materialdetail.php',
            data: {
                'kode_rak': kode_rak
            },
            success: function(response) {
                $('#div_dashboard2').html(response);
            }
        });
    });
</script>


<script>
    $('table tbody tr').on('click', 'td:eq(0)', function() {
        var kode_rak = $(this).closest('tr').find('td:eq(0)').attr('value');
        $('#div_dashboard3').empty()
        $.ajax({
            type: 'POST',
            url: 'get_rakdetail.php',
            data: {
                'kode_rak': kode_rak
            },
            success: function(response) {
                $('#div_dashboard3').html(response);
            }
        });
    });
</script>


<script>
    <?php include '../../include/conn.php';
    $sql = mysqli_query($conn_li, "select kode_rak,nama_rak,kapasitas, terisi, persen, sisa from dsb_whs limit 1");
    // $sql = mysqli_query($conn_li, "select kode_rak,nama_rak,date_input,kapasitas,COALESCE(qty,0) qty,round((COALESCE(qty,0) / kapasitas * 100),2) persen , (kapasitas - COALESCE(qty,0)) sisa from (select * from (select kode_rak,nama_rak, kapasitas from m_rak) a left join (select kode_rak koderak,count(roll_qty) as qty, max(date_input) date_input from in_material_det where cancel = 'N' group by kode_rak) b on a.kode_rak = b.koderak) a where a.kode_rak = (select kode_rak from m_rak order by kode_rak asc limit 1)");    
    foreach ($sql as $data) : ?>
        var datapersen = <?= $data["persen"]; ?>;
    <?php endforeach; ?>
    var data2 = datapersen;
    var options = {
        chart: {
            height: 250,
            type: "radialBar",
        },

        series: [data2],
        colors: ["#20E647"],
        plotOptions: {
            radialBar: {
                hollow: {
                    margin: 0,
                    size: "70%",
                    background: "#293450"
                },
                track: {
                    dropShadow: {
                        enabled: true,
                        top: 2,
                        left: 0,
                        blur: 4,
                        opacity: 0.15
                    }
                },
                dataLabels: {
                    name: {
                        offsetY: -10,
                        color: "#fff",
                        fontSize: "13px"
                    },
                    value: {
                        color: "#fff",
                        fontSize: "30px",
                        show: true
                    }
                }
            }
        },
        fill: {
            type: "gradient",
            gradient: {
                shade: "dark",
                type: "vertical",
                gradientToColors: ["#87D4F9"],
                stops: [0, 100]
            }
        },
        stroke: {
            lineCap: "round"
        },
        labels: ["Digunakan"]
    };

    var chart = new ApexCharts(document.querySelector("#chart_dashboard1"), options);

    chart.render();
</script>
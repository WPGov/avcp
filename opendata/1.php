<?php

    $lista_procedure = array();


        query_posts( array( 'post_type' => 'avcp', 'posts_per_page' => '-1', 'annirif' => '') ); global $post;

        $numero_gare_{$term->name} = 0;
        $tot_agg_{$term->name} = 0;
        $tot_liq_{$term->name} = 0;
        $gap_tot_giorni_{$term->name} = 0;

        while ( have_posts() ) : the_post();
                $modalita_contraente = get_post_meta(get_the_ID(), 'avcp_contraente', true);

                if ($modalita_contraente == null) {}

                // $counter_{} salva in memoria il numero di occorrenze trovate
                $counter_{$modalita_contraente}++;

                // $lista_procedure salva in memoria le modalità trovate nella scansione (nome)
                if (!in_array($modalita_contraente, $lista_procedure)) {
                    array_push($lista_procedure, $modalita_contraente);
                }

        endwhile;
    wp_reset_query();

?>
 <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([

            <?php
                for ($i = 0; $i < count($lista_procedure); $i++) {
                    echo '[\'' . $lista_procedure[$i] . '\', ' . $counter_{$lista_procedure[$i]} . ']';
                    if ($i != count($lista_procedure) -1) { echo ','; }
                }
            ?>
        ]);

        // Set chart options
        var options = {'title':'Modalità di scelta del contraente per i Bandi di Gara <?php if ($anno) { echo 'del ' . $anno; } ?>'};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
    <div id="chart_div" style="width:100%; height:300px"></div>
<?php



?>

<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization',
       'version':'1','packages':['timeline']}]}"></script>
<script type="text/javascript">

google.setOnLoadCallback(drawChart);
function drawChart() {
  var container = document.getElementById('example2.1');
  var chart = new google.visualization.Timeline(container);
  var dataTable = new google.visualization.DataTable();

  dataTable.addColumn({ type: 'string', id: 'Term' });
  dataTable.addColumn({ type: 'string', id: 'Name' });
  dataTable.addColumn({ type: 'date', id: 'Start' });
  dataTable.addColumn({ type: 'date', id: 'End' });

  dataTable.addRows([

      <?php
        query_posts( array( 'post_type' => 'avcp', 'limit', '1', 'posts_per_page' => '-1', 'annirif' => $anno) ); global $post;

        while ( have_posts() ) : the_post();
            if (!get_post_meta(get_the_ID(), 'avcp_data_fine', true) == null) {
                echo '[ \'' . $o++ . '\', \'Washington\',
                    new Date(' .
                        date("Y", strtotime(get_post_meta(get_the_ID(), 'avcp_data_inizio', true))) .', '.
                        date("m", strtotime(get_post_meta(get_the_ID(), 'avcp_data_inizio', true))) .', '.
                        date("d", strtotime(get_post_meta(get_the_ID(), 'avcp_data_inizio', true))) .'),
                    new Date(' .
                        date("Y", strtotime(get_post_meta(get_the_ID(), 'avcp_data_fine', true))) .', '.
                        date("m", strtotime(get_post_meta(get_the_ID(), 'avcp_data_fine', true))) .', '.
                        date("d", strtotime(get_post_meta(get_the_ID(), 'avcp_data_fine', true))) .')],
                        ';
            }
        endwhile;

        wp_reset_query();
      ?>]);

  chart.draw(dataTable);
}
</script>

<div id="example2.1" style="width: 100%; height: 200px;"></div>

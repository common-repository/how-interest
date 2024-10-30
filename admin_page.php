<?php

/*	
 	
	Copyright 2011  howlin.ie  (email : hello@howlin.ie)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	
*/



 /**
  * Decalre initial $limit and $offset values
  * 
  */
 $limit 		= 10;
 $offset 	= 0;
 
 if($_GET['paged'] > 1) $offset = ($_GET['paged'] - 1) * $limit;

 //----------------------------------------------------------------
 
 
 
 
 

 /**
  * get list of interested users
  * 
  */ 
 global $wpdb;
 $table_name 	= $wpdb->prefix . "how_interested";
 $sql				= "SELECT * FROM `".$table_name."` ORDER BY `date` DESC LIMIT ".$offset.",".$limit;
 $query 			= $wpdb->get_results($sql);
 $count 			= $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".$table_name."`"));
 
 //----------------------------------------------------------------

 
 
 
 
 
 /**
  * pagination 
  * 
  */
 $_GET['paged'] > 1 ? $current = $_GET['paged'] : $current = 1;
 $calc_pages = $count / $limit;
 $args = array(
    'base' 			 => @add_query_arg('paged','%#%'),
    'total'        => (round($calc_pages, 0) < $calc_pages) ? (round($calc_pages, 0)+1) : round($calc_pages),
    'current'      => $current,
    'show_all'     => true,
    'type'         => 'plain',
    'add_args'     => 'page=how_interest'
 ); 
 
 //----------------------------------------------------------------
 
 
 
 
 

 /**
  * set column heads for the table
  * 
  */
 $columns = array (
 	'name'		=> 'Name',
 	'email'		=> 'Email',
 	'phone'		=> 'Phone',
 	'date'		=> 'Date Entered'
 );
 register_column_headers('how_interested_users', $columns)

 //----------------------------------------------------------------

 
 
 
 
 
 
 
 
 
 
 
 
?>

<div class='wrap'>
	<h2><?php _e('\'How - Interest\'  -  User Data', 'how_interest'); ?></h2>
	<p><?php _e('Below is a list of people who registered their interest on you site through the \'How - Interest\' widget'); ?></p>
	
	<div class="tablenav"><div class='tablenav-pages'>
		<?php echo paginate_links( $args ); ?> 
	</div></div>
	<table class="wp-list-table widefat  pages" cellspacing="0">
		<thead>
			<tr>
				<?php print_column_headers('how_interested_users')?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<?php print_column_headers('how_interested_users')?>
			</tr>
		</tfoot>
		<tbody>
			
			<?php if($query):?>
				<?php foreach ($query as $row): ?>
				<tr>
					<td><?php echo $row->name; ?></td>
					<td><a href='mailto:<?php echo $row->email; ?>'><?php echo $row->email; ?></a></td>
					<td><?php echo $row->phone; ?></td>
					<td><?php echo date('dS M Y @ H:i:s', $row->date); ?></td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="3"> No results to display</td>
				</tr>
			<?php endif;?>
			
		</tbody>
	</table>
	<div class="tablenav"><div class='tablenav-pages'>
		<?php echo paginate_links( $args ); ?> 
	</div></div>
	
</div>




<?php 
/* End of file admin_page.php */
?>
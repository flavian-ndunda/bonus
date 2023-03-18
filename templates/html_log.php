<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?><html>
<?php if (isset($_GET['display'])) { ?>
<style>
body {
	font-family: sans-serif;
	font-size: .95em;
}
table,th,td {
	font: inherit;
	border: 1px solid lightgrey;
	border-collapse: collapse;
}
th,td {
	padding: .5em;
}
th {
	font-weight: bold;
}
select {
	padding: .5em;
	width: 5em;
}
</style>
<?php } ?>
<?php if (isset($_GET['download'])) { ?>
<style>
table,th,td {
	border: 1px solid whitesmoke;
	border-collapse: collapse;
}
</style>
<?php } ?>
<body>

<?php if (isset($_GET['display'])) { ?>
<p><a href="admin.php?page=couponwheel_dashboard&action=configure_wheel&wheel_hash=<?php echo $_GET['wheel_hash'] ?>#couponwheel_configre_wheel_tabs_data_collection"><< Return to WordPress Dashboard</a></p>
<?php } ?>

<table>
	<tr>
		<?php foreach($fields as $field) { ?>
		<th><?php echo $field ?></th>
		<?php } ?>
	</tr>
	<?php foreach($rows as $row) { ?>
	<tr>
		<?php foreach($fields as $field) { ?>
		<td><?php echo esc_html($row->{$field}) ?></td>
		<?php } ?>
	</tr>
	<?php } ?>
</table>

</body>
</html>
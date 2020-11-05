<html>
<body>
<a href="/">Back</a>


<div style="font-size: 1.5em">
    <?php echo "<h2>{$stock->name()}, {$stock->symbol()}</h2>";?><br>
    <?php echo 'Open: ' . $stock->open();?><br>
    <?php echo 'Close: ' . $stock->close();?><br>
    <?php echo 'Volume: ' . $stock->volume();?><br>
</div>
</body>
</html>
<table border="1">
    <?php foreach ($response as $item) :?>
        <tr>
            <td><?php echo $item->result['id']?></td>
            <td><?php echo $item->result['name']?></td>
            <td><?php echo $item->result['age']?></td>
            <td><?php echo $item->result['text']?></td>
            <td><?php echo $item->result['created_at']?></td>
            <td><?php echo $item->result['updated_at']?></td>
        </tr>
    <?php endforeach;?>
</table>
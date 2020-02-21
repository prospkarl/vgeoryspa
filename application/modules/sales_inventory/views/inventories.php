<?php if (!empty($result_months)): ?>
  <?php foreach ($result_months as $month_info): ?>
    <tr>
      <td><?= $month_info['month'] ?></td>
      <td>
        <?php if ($month_info['inprogress']): ?>
          <span class="label label-warning label-rounded">In Progress</span>
        <?php else: ?>
          <span class="label label-info label-rounded">Completed</span>
        <?php endif; ?>
      </td>
      <td><a href="<?= base_url('sales_inventory/view_month/' . $month_info['number'] . '/' . $month_info['year']) ?>" class="btn btn-sm btn-info inventory-link">View</a></td>
    </tr>
  <?php endforeach; ?>
<?php else: ?>
  <tr>
    <td colspan="100%">No results found</td>
  </tr>
<?php endif; ?>

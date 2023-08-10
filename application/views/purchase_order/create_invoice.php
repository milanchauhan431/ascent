<form>
    <div class="col-md-12">
        <div class="row">
        <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-info">
                        <tr>
                            <th>#</th>
                            <th>Job No.</th>
                            <th>Cat No.</th>
                            <th>Item Name</th>
                            <th>Make</th>
                            <th>Pending Qty.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=1;
                            foreach($reqItemList as $row):
                                $row->row_index = "";
                                echo "<tr>
                                    <td class='text-center'>
                                        <input type='checkbox' id='md_checkbox_" . $i . "' class='filled-in chk-col-success orderItem' data-row='".json_encode($row)."' ><label for='md_checkbox_" . $i . "' class='mr-3 check" . $row->ref_id . "'></label>
                                    </td>
                                    <td>".$row->job_number."</td>
                                    <td>".$row->item_code."</td>
                                    <td>".$row->item_name."</td>
                                    <td>".$row->make."</td>
                                    <td>".floatval($row->qty)."</td>
                                </tr>";
                                $i++;
                            endforeach;
                        ?>
                    </tbody>
                </table>
            </div>        
        </div>
    </div>
</form>
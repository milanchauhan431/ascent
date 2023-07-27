<form>
    <div class="col-md-12">
        <div class="row">
            <div class="table table-responsive">
                <table class="table table-borderless">
                    <thead class="thead-info">
                        <th style="width:50%;">Part Name</th>
                        <th style="width:50%;">Qty.</th>
                    </thead>
                    <tbody>
                        <?php
                            foreach($dataRow as $row):
                                $param_name = ucwords(str_replace("_"," ",$row->param_key));
                                if($row->param_key == "total_parts"):
                                    echo '<tr class="thead-info">
                                        <th>'.$param_name.'</th>
                                        <th>'.$row->param_value.'</th>
                                    </tr>';
                                elseif($row->param_key == "cutting_drawings"):
                                    echo '<tr>
                                        <td>'.$param_name.'</td>
                                        <td>'.((!empty($row->param_value))?'<a href="'.base_url('assets/uploads/production/'.$row->param_value).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':"").'</td>
                                    </tr>';
                                else:
                                    echo '<tr>
                                        <td>'.$param_name.'</td>
                                        <td>'.$row->param_value.'</td>
                                    </tr>';
                                endif;
                            endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
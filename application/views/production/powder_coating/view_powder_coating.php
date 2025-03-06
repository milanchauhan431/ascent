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
                                if(in_array($row->param_key,["total_parts"])):
                                    echo '<tr class="thead-info">
                                        <th>'.$param_name.'</th>
                                        <th>'.$row->param_value.'</th>
                                    </tr>';
                                else:
                                    if(in_array($row->param_key,["discription_of_powder","discription_of_powder_2","discription_of_powder_3","discription_of_powder_4","discription_of_powder_5"])):
                                        echo '<tr>
                                            <td>'.$param_name.'</td>
                                            <td>'.((!empty($row->param_value))?$this->item->getItem(['id'=>$row->param_value])->item_name:"").'</td>
                                        </tr>';
                                    else:
                                        echo '<tr>
                                            <td>'.$param_name.'</td>
                                            <td>'.$row->param_value.'</td>
                                        </tr>';
                                    endif;
                                endif;
                            endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
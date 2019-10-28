

    <table class="table table-condensed">
        <thead>
            <tr>
                <th scope="col">N° Pago</th>                                
                <th scope="col">Estudiante</th>                                                
                <th scope="col">Pago</th>
                <th scope="col">Tipo Pago</th>                
                <th scope="col">Valor Pago</th>
                <th scope="col">Fecha Pago</th>
                <th scope="col">Sede</th>
                <th scope="col">Nivel</th>                
                <th scope="col">Observaciones</th>
                <th scope="col">Anulado</th>                                               
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model as $val): ?>
                <tr>
                    <?php if ($val->anulado == "") {
                        $anulado = "NO";
                    } else {
                        $anulado = "SI";
                    } ?>
                    <?php if ($val->sede == "") {
                        $sede = "sin definir";
                    } else {
                        $sede = $val->sede;
                    } ?>
                    <th scope="row"><?= $val->nropago ?></th>                
                    <td><?= $val->entificacion->nombreestudiante ?></td>                                                                    
                    <td><?= $val->mensualidad ?></td>
                    <td><?= $val->ttpago ?></td>                    
                    <td><?= number_format($val->total) ?></td>
                    <td><?= $val->fecha_registro ?></td>
                    <td><?= $sede ?></td>
                    <td><?= $val->nivel ?></td>                    
                    <td><?= $val->observaciones ?></td>
                    <td align="center"><?= $anulado ?></td>                        
                </tr>
        </tbody>
            <?php endforeach; ?>
    </table>    
    
<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información Evento</legend>

    <div class="formulario__campo">
        <label for="nombre" class="formulario__label">Nombre Evento</label>
        <input 
            type="text"
            class="formulario__input"
            id="nombre"
            name="nombre"
            placeholder="Nombre Evento"
            value="<?php echo $evento->nombre; ?>"
        />
    </div>
    
    <div class="formulario__campo">
        <label for="descripcion" class="formulario__label">Descripción Evento</label>
        <textarea 
            rows= "5"
            class="formulario__input"
            id="descripcion"
            name="descripcion"
            placeholder="Descripción Evento"
            value="<?php echo $evento->descripcion; ?>"
        ><?php echo $evento->descripcion; ?>
        </textarea>
    </div>

    <div class="formulario__campo">
        <label for="categoria" class="formulario__label">Categoría o tipo de Evento</label>
        <select 
            class="formulario__select"
            id="categoria" 
            name="categoria_id"
        >
            <option value="">- Seleccionar -</option>
            <!-- genera las opciones,tras iterar el arreglo $categorias, obtenido de la dB, (enviado en el render a crear.php) -->
            <?php foreach($categorias as $categoria ) { ?>
                <!-- para asignar la opción 'selected', compara el id de $evento con el id de la opción $categoria -->
                <option <?php echo ($evento->categoria_id === $categoria->id) ? 'selected' : ''; ?> value="<?php echo $categoria->id; ?>"><?php echo $categoria->nombre; ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="formulario__campo">
        <label for="formulario" class="formulario__label">Selecciona el día</label>
        <!-- genera las opciones,tras iterar el arreglo $dias, obtenido de la dB, (enviado en el render a crear.php) -->
        <div class="formulario__radio">
            <?php foreach($dias as $dia){ ?>
                <div> 
                    <label for="<?php echo strtolower($dia->nombre); ?>"><?php echo $dia->nombre; ?></label>
                    <input 
                        type="radio"
                        id="<?php echo strtolower($dia->nombre); ?>"
                        name="dia"
                        value="<?php echo $dia->id; ?>"
                    />
                </div>
            <?php } ?>
        </div>

        <!-- input oculto para asignar el id del día selecionado (dia_id), para guardar en la DB -->
        <input type="hidden" name="dia_id" value="">

    </div>
    

    <!-- sobre esta sección con id=horas se aplicará código JS -->
    <div id="horas" class="formulario__campo">
        <label class="formulario__label">Seleccionar Horario</label>

        <ul id="horas" class="horas">
            <?php foreach($horas as $hora) { ?>
                <!-- data-hora-id es un atributo personalizado -->
                <li data-hora-id="<?php echo $hora->id; ?>" class="horas__hora horas__hora--deshabilitada"><?php echo $hora->hora; ?></li>
            <?php } ?>
        </ul>

        <!-- input oculto para asignar el id de la hora selecionada (hora_id), para guardar en la DB -->
        <input type="hidden" name="hora_id" value="">

    </div>
</fieldset>

<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información Extra</legend>

    <div class="formulario__campo">
        <label for="ponentes" class="formulario__label">Ponente</label>
        <input 
            type="text"
            class="formulario__input"
            id="ponentes"
            placeholder="Buscar Ponente"
        />
        <!-- para insertar los li de los ponentes encotrados, desde ponentes.js -->
        <ul id="listado-ponentes" class="listado-ponentes">
                
        </ul>
    </div>

    <div class="formulario__campo">
        <label for="disponibles" class="formulario__label">Lugares Disponibles</label>
        <input 
            type="number"
            min="1"
            class="formulario__input"
            id="disponibles"
            name="disponibles"
            placeholder="Ej. 20"
            value="<?php echo $evento->disponibles; ?>"
        />
    </div>
</fieldset>
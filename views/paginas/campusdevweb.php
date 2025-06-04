<main class="devwebcamp">
    <h2 class="devwebcamp__heading"><?php echo $titulo; ?></h2>
    <p class="devwebcamp__descripcion">Conoce el ciclo de conferencias del año</p>

    <div class="devwebcamp__grid">
        <div class="devwebcamp__imagen">
            <!-- picture ofrece cargar formatos de imagen, según el orden -->
            <picture>
                <source srcset="build/img/sobre_devwebcamp.avif" type="image/avif">
                <source srcset="build/img/sobre_devwebcamp.webp" type="image/webp">
                <img loading="lazy" width="200" height="300" src="build/img/sobre_devwebcamp.jpg" alt="imagen campus">
            </picture>
        </div>

        <div class="devwebcamp__contenido">
            <p class="devwebcamp__texto">
                There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.
            </p>
            <p class="devwebcamp__texto">
                Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words.
            </p>
        </div>

    </div>
</main>
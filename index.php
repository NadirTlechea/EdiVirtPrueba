<?php
// Conexión a la base de datos Edivirt
$conexion = new mysqli("localhost", "root", "", "edivirt");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para obtener las 5 noticias más recientes con imágenes
$sql = "SELECT n.id, n.titulo, n.descripcion, n.url_imagen, n.fecha_publicacion, 
               u.username as autor_nombre 
        FROM noticias n
        JOIN usuarios u ON n.autor_id = u.id
        WHERE n.url_imagen IS NOT NULL AND n.url_imagen != ''
        ORDER BY n.fecha_publicacion DESC
        LIMIT 5";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EdiVirt</title>
    <style>
        /* Estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            color: white;
            overflow-x: hidden;
        }
        
        /* Fondo con imagen y overlay */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://i.pinimg.com/736x/22/fa/bc/22fabcf0eedf4ebbbacdef9c91e6bcb2.jpg');
            background-size: cover;
            background-position: center;
            z-index: -2;
        }
        
        .background::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            z-index: -1;
        }
        
        /* Barra de navegación transparente */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 30px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logo {
            font-size: 35px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        .nav-links {
            display: flex;
            gap: 40px;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            letter-spacing: 1px;
            position: relative;
            padding: 5px 0;
            transition: all 0.3s ease;
        }
        
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: white;
            transition: width 0.3s ease;
        }
        
        .nav-links a:hover::after {
            width: 100%;
        }
        
        /* Encabezado */
        .header {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 10%;
        }
        
        .tagline {
            font-family: 'Playfair Display', serif;
            font-size: 5vw;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        
        .tagline span {
            display: block;
        }
        
        /* Galería de imágenes */
        .gallery {
            padding: 100px 5%;
            background: rgba(0, 0, 0, 0.5);
        }
        
        .gallery-title {
            font-size: 2rem;
            margin-bottom: 50px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        
        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            max-height: 70vh;
            overflow-y: auto;
            padding: 20px;
        }
        
        .image-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            aspect-ratio: 4/3;
            transition: transform 0.3s ease;
        }
        
        .image-item:hover {
            transform: scale(1.03);
        }
        
        .image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .image-item:hover img {
            transform: scale(1.1);
        }
        
        .image-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 20px;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }
        
        .image-item:hover .image-caption {
            transform: translateY(0);
        }
        
        /* Barra de desplazamiento personalizada */
        .image-grid::-webkit-scrollbar {
            width: 8px;
        }
        
        .image-grid::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        
        .image-grid::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }
        
        .image-grid::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .tagline {
                font-size: 8vw;
            }
            
            .nav-links {
                gap: 20px;
            }
            
            .image-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Fondo -->
    <div class="background"></div>
    
    <!-- Navegación -->
    <nav>
        <div class="logo">EDIVIRT</div>
        <div class="nav-links">
            <a href="Noticias/PHP/verV2.php">NOTICIAS</a>
            <a href="Eventos/PHP/calendariov2.php">EVENTOS</a>
            <a href="Proyectos/PHP/galeria.php">PROYECTOS</a>
            <a href="#">ACERCA DE NOSOTROS</a>
            <a href="login.php">INICIAR SESIÓN</a>
            <!-- <a href="#">CONTACT</a> -->
        </div>
    </nav>
    
    <!-- Encabezado -->
    <div class="header">
        <h1 class="tagline">
            <span>LA VOZ</span>
            <span>DE</span>
            <span>LOS</span>
            <span>ESTUDIANTES</span>
        </h1>
    </div>
    
    <!-- 5 primeras Noticias -->
    <section class="gallery">
        <h2 class="gallery-title">Noticias Recientes</h2>
        <div class="image-grid">
            <?php if ($resultado->num_rows > 0): ?>
                <?php while($noticia = $resultado->fetch_assoc()): ?>
                    <div class="image-item">
                        <img src="<?php echo htmlspecialchars($noticia['url_imagen']); ?>" 
                             alt="<?php echo htmlspecialchars($noticia['titulo']); ?>">
                        <div class="image-caption">
                            <h3><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; color: #ccc;">
                    No hay noticias con imágenes disponibles.
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <script>
        // Efecto de aparición suave al hacer scroll
        const imageItems = document.querySelectorAll('.image-item');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        imageItems.forEach(item => {
            item.style.opacity = 0;
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(item);
        });
    </script>
    
    <!-- Fuentes de Google -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet>
</body>
</html>

<?php
$conexion->close();
?>
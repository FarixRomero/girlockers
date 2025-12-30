# Configuración de S3 para Archivos Públicos

## Estado Actual
✅ El código de Laravel está configurado para subir archivos con ACL `public-read`
✅ La configuración del disco S3 incluye `visibility => 'public'`

## Configuración Requerida en AWS S3

Para que los archivos sean accesibles públicamente, necesitas configurar el bucket `fx-proyectos-gl` en AWS:

### 1. Desbloquear acceso público del bucket

En la consola de AWS S3:

1. Ve a tu bucket: `fx-proyectos-gl`
2. Ve a la pestaña **"Permissions"** (Permisos)
3. En **"Block public access (bucket settings)"**, haz clic en **Edit**
4. **Desmarca** todas las opciones:
   - ☐ Block all public access
   - ☐ Block public access to buckets and objects granted through new access control lists (ACLs)
   - ☐ Block public access to buckets and objects granted through any access control lists (ACLs)
   - ☐ Block public access to buckets and objects granted through new public bucket or access point policies
   - ☐ Block public and cross-account access to buckets and objects through any public bucket or access point policies
5. Haz clic en **Save changes**
6. Confirma escribiendo "confirm"

### 2. Habilitar ACLs en el bucket

1. En la misma pestaña **"Permissions"**
2. Busca la sección **"Object Ownership"**
3. Haz clic en **Edit**
4. Selecciona: **"ACLs enabled"**
5. Selecciona: **"Bucket owner preferred"** o **"Object writer"**
6. Marca la casilla de confirmación
7. Haz clic en **Save changes**

### 3. Configurar Bucket Policy (opcional pero recomendado)

Para asegurar que todos los objetos sean públicos, agrega esta política:

```json
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Sid": "PublicReadGetObject",
            "Effect": "Allow",
            "Principal": "*",
            "Action": "s3:GetObject",
            "Resource": "arn:aws:s3:::fx-proyectos-gl/*"
        }
    ]
}
```

Cómo aplicarla:
1. En la pestaña **"Permissions"**
2. Busca **"Bucket policy"**
3. Haz clic en **Edit**
4. Pega el JSON de arriba
5. Haz clic en **Save changes**

### 4. Configurar CORS (para subidas desde el navegador)

Si planeas subir archivos directamente desde el navegador, configura CORS:

1. Ve a la pestaña **"Permissions"**
2. Busca **"Cross-origin resource sharing (CORS)"**
3. Haz clic en **Edit**
4. Pega esta configuración:

```json
[
    {
        "AllowedHeaders": ["*"],
        "AllowedMethods": ["GET", "PUT", "POST", "DELETE", "HEAD"],
        "AllowedOrigins": ["*"],
        "ExposeHeaders": ["ETag"],
        "MaxAgeSeconds": 3000
    }
]
```

5. Haz clic en **Save changes**

## Verificación

Después de configurar, prueba subiendo una imagen desde el admin de Girls Lockers.

La URL debería ser accesible públicamente:
- Formato: `https://fx-proyectos-gl.s3.amazonaws.com/path/to/file.jpg`
- Debería poder verse en el navegador sin autenticación

## Alternativa: CloudFront (Recomendado para producción)

En lugar de exponer S3 directamente, puedes crear una distribución de CloudFront:

**Ventajas:**
- URLs más rápidas (CDN global)
- HTTPS automático con certificado
- Mejor control de caché
- Dominio personalizado (ej: `cdn.girlslockers.com`)

**Configuración:**
1. Crear distribución de CloudFront apuntando a tu bucket S3
2. Bucket S3 puede permanecer privado
3. CloudFront sirve los archivos públicamente
4. Actualizar `AWS_URL` en `.env` con la URL de CloudFront

## Seguridad

⚠️ **Importante**: Solo los archivos de la aplicación (imágenes, thumbnails) serán públicos.
✅ El bucket NO expone archivos privados porque cada archivo se sube explícitamente con ACL público.

## Troubleshooting

### Error: "Access Denied" al ver imagen
- Verifica que hayas desbloqueado el acceso público del bucket
- Verifica que ACLs estén habilitadas
- Revisa la política del bucket

### Error: "The bucket does not allow ACLs"
- Ve a "Object Ownership" y cambia a "ACLs enabled"

### URLs no funcionan
- Verifica que `AWS_URL` en `.env` sea correcto
- Prueba la URL directamente en el navegador

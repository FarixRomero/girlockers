# 📄 Product Requirements Document (PRD)

**Proyecto:** Girl Lockers – Escuela Internacional de Locking
**Versión:** 1.0
**Fecha:** 30/09/2025

---

## 1. 🎯 Visión del Producto

Girl Lockers es una plataforma web creada para **empoderar, conectar e inspirar** a chicas lockers de todo el mundo, ofreciendo cursos en video de locking organizados en módulos y lecciones.
Los usuarios podrán registrarse, acceder a clases de prueba y, tras validación del administrador, obtener acceso completo a todo el contenido.

---

## 2. 👥 Roles de Usuario

### **Estudiante**

* Puede registrarse de manera autónoma en la plataforma.
* Accede a una o varias clases de prueba gratuitas.
* Puede ver todo el contenido cuando el admin habilita su cuenta.
* Puede comentar en foros de cada lección (solo texto).
* Puede dar “like” a una lección.

### **Administrador**

* Recibe notificación cuando un estudiante solicita acceso completo.
* Habilita o deshabilita el acceso de estudiantes.
* Administra cursos, módulos, lecciones y videos.
* Modera los foros de comentarios.

---

## 3. 📚 Contenido y Estructura

* **Cursos** → Unidad principal de enseñanza.
* **Módulos** → Agrupación de lecciones dentro de cada curso.
* **Lecciones** → Video + foro de comentarios + opción de like.

### Atributos:

* Curso: título, descripción, nivel, imagen de portada.
* Módulo: nombre, número de orden.
* Lección: título, descripción corta, video, foro de comentarios, contador de likes.

---

## 4. 💬 Interacción

* **Foros por lección**:

  * Solo texto.
  * Ordenados por fecha.
  * Admin puede borrar comentarios.

* **Likes en lecciones**:

  * Botón de reacción positiva única por usuario.

---

## 5. 🔒 Acceso y Monetización

* Registro abierto para estudiantes.
* Clases de prueba disponibles sin validación.
* Acceso completo habilitado manualmente por el admin tras confirmar pago **fuera de la plataforma**.
* Sin restricciones de dispositivos: el usuario puede ingresar desde cualquier navegador.

---

## 6. 🛠️ Requisitos Técnicos

* **Stack tecnológico**:

  * Backend: Laravel
  * Frontend: Livewire
  * Base de datos: MySQL o PostgreSQL

* **Características obligatorias**:

  * Sistema de autenticación (registro/login con email).
  * Gestión de usuarios (roles: admin y estudiante).
  * CRUD de cursos, módulos y lecciones.
  * Integración de video en las lecciones.
  * Sistema de notificaciones internas al admin (cuando un usuario pide acceso completo).
  * Foro de comentarios por lección.
  * Likes en lecciones.

---

## 7. ✅ Criterios de Éxito

* Los usuarios pueden registrarse y acceder automáticamente a clases de prueba.
* El admin recibe notificación de nuevas solicitudes de acceso.
* El admin puede habilitar cuentas sin fricción.
* Los estudiantes pueden consumir videos y participar en foros.
* El sistema de likes funciona en cada lección.


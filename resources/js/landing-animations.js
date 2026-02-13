import { animate, scroll, stagger, inView } from "motion";

// Initialize animations when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Motion.dev Animations para la sección Nuestra Misión

    const missionSection = document.querySelector("#mission");

    // Animación de entrada con scroll para el título
    const missionTitle = document.querySelector(".mission-title");
    if (missionSection && missionTitle) {
        scroll(
            animate(missionTitle, {
                opacity: [0, 1],
                y: [50, 0],
            }),
            { target: missionSection, offset: ["start end", "start center"] }
        );
    }

    // Animación de entrada para la imagen única (móvil)
    const missionImage = document.querySelector(".mission-image");
    if (missionSection && missionImage) {
        scroll(
            animate(missionImage, {
                opacity: [0, 1],
                scale: [0.9, 1],
                rotateY: [-15, 0],
            }),
            { target: missionSection, offset: ["start end", "start center"] }
        );
    }

    // Animaciones para el grid de imágenes (desktop)
    const galleryItem1 = document.querySelector(".mission-gallery-item-1");
    const galleryItem2 = document.querySelector(".mission-gallery-item-2");
    const galleryItem3 = document.querySelector(".mission-gallery-item-3");
    const galleryItem4 = document.querySelector(".mission-gallery-item-4");

    // Animación de entrada escalonada para el grid
    if (missionSection && galleryItem1) {
        inView(galleryItem1, () => {
            animate(
                galleryItem1,
                {
                    opacity: [0, 1],
                    y: [60, 0],
                    rotateZ: [-3, 0]
                },
                { duration: 0.8, delay: 0.1 }
            );
        }, { amount: 0.3 });
    }

    if (missionSection && galleryItem2) {
        inView(galleryItem2, () => {
            animate(
                galleryItem2,
                {
                    opacity: [0, 1],
                    y: [80, 0],
                    rotateZ: [3, 0]
                },
                { duration: 0.8, delay: 0.2 }
            );
        }, { amount: 0.3 });
    }

    if (missionSection && galleryItem3) {
        inView(galleryItem3, () => {
            animate(
                galleryItem3,
                {
                    opacity: [0, 1],
                    y: [70, 0],
                    rotateZ: [2, 0]
                },
                { duration: 0.8, delay: 0.3 }
            );
        }, { amount: 0.3 });
    }

    if (missionSection && galleryItem4) {
        inView(galleryItem4, () => {
            animate(
                galleryItem4,
                {
                    opacity: [0, 1],
                    y: [90, 0],
                    rotateZ: [-2, 0]
                },
                { duration: 0.8, delay: 0.4 }
            );
        }, { amount: 0.3 });
    }

    // Animaciones de scroll parallax para el grid
    if (missionSection && galleryItem1) {
        scroll(
            animate(galleryItem1, {
                y: [0, -30],
            }),
            { target: missionSection, offset: ["start end", "end start"] }
        );
    }

    if (missionSection && galleryItem2) {
        scroll(
            animate(galleryItem2, {
                y: [0, -50],
            }),
            { target: missionSection, offset: ["start end", "end start"] }
        );
    }

    if (missionSection && galleryItem3) {
        scroll(
            animate(galleryItem3, {
                y: [0, -40],
            }),
            { target: missionSection, offset: ["start end", "end start"] }
        );
    }

    if (missionSection && galleryItem4) {
        scroll(
            animate(galleryItem4, {
                y: [0, -60],
            }),
            { target: missionSection, offset: ["start end", "end start"] }
        );
    }

    // Hover animations para las imágenes del grid
    [galleryItem1, galleryItem2, galleryItem3, galleryItem4].forEach((item, index) => {
        if (item) {
            item.addEventListener('mouseenter', () => {
                animate(item, {
                    scale: 1.05,
                    rotateZ: index % 2 === 0 ? 2 : -2,
                }, { duration: 0.4, easing: "ease-out" });
            });

            item.addEventListener('mouseleave', () => {
                animate(item, {
                    scale: 1,
                    rotateZ: 0,
                }, { duration: 0.4, easing: "ease-out" });
            });
        }
    });

    // Animación de entrada escalonada para los párrafos de texto
    const missionText1 = document.querySelector(".mission-text-1");
    const missionText2 = document.querySelector(".mission-text-2");
    const missionText3 = document.querySelector(".mission-text-3");

    if (missionSection && missionText1) {
        inView(missionText1, () => {
            animate(
                missionText1,
                { opacity: [0, 1], x: [-50, 0] },
                { duration: 0.6, delay: 0.1 }
            );
        }, { amount: 0.3 });
    }

    if (missionSection && missionText2) {
        inView(missionText2, () => {
            animate(
                missionText2,
                { opacity: [0, 1], x: [-50, 0] },
                { duration: 0.6, delay: 0.2 }
            );
        }, { amount: 0.3 });
    }

    if (missionSection && missionText3) {
        inView(missionText3, () => {
            animate(
                missionText3,
                { opacity: [0, 1], x: [-50, 0] },
                { duration: 0.6, delay: 0.3 }
            );
        }, { amount: 0.3 });
    }

    // Animación escalonada para las estadísticas
    const missionStat1 = document.querySelector(".mission-stat-1");
    const missionStat2 = document.querySelector(".mission-stat-2");
    const missionStat3 = document.querySelector(".mission-stat-3");

    if (missionSection && missionStat1 && missionStat2 && missionStat3) {
        inView(missionStat1, () => {
            const stats = [missionStat1, missionStat2, missionStat3];
            animate(
                stats,
                {
                    opacity: [0, 1],
                    y: [30, 0],
                    scale: [0.8, 1]
                },
                {
                    duration: 0.5,
                    delay: stagger(0.1, { start: 0.4 })
                }
            );
        }, { amount: 0.3 });
    }

    // Hover animations para las estadísticas
    [missionStat1, missionStat2, missionStat3].forEach(element => {
        if (element) {
            element.addEventListener('mouseenter', () => {
                animate(element, {
                    scale: 1.1,
                    y: -5
                }, { duration: 0.3, easing: "ease-out" });
            });

            element.addEventListener('mouseleave', () => {
                animate(element, {
                    scale: 1,
                    y: 0
                }, { duration: 0.3, easing: "ease-out" });
            });
        }
    });

    // Animaciones para los candados decorativos - floating effect con Motion
    const lockSelectors = [
        ".mission-lock-1", ".mission-lock-2", ".mission-lock-3",
        ".mission-lock-4", ".mission-lock-5", ".mission-lock-6"
    ];

    lockSelectors.forEach((selector, index) => {
        const element = document.querySelector(selector);
        if (element) {
            // Animación de entrada
            inView(element, () => {
                animate(
                    element,
                    {
                        opacity: [0, 0.1],
                        scale: [0, 1],
                        rotateZ: [0, 360]
                    },
                    { duration: 1, delay: index * 0.1 }
                ).finished.then(() => {
                    // Floating loop animation
                    animate(
                        element,
                        {
                            y: [0, -20, 0],
                            rotateZ: [-5, 5, -5]
                        },
                        {
                            duration: 3 + (index * 0.5),
                            repeat: Infinity,
                            easing: "ease-in-out"
                        }
                    );
                });
            }, { amount: 0.3 });
        }
    });

    // Animaciones para los blobs con parallax effect
    const blob1 = document.querySelector(".mission-blob-1");
    const blob2 = document.querySelector(".mission-blob-2");
    const blob3 = document.querySelector(".mission-blob-3");

    if (missionSection && blob1) {
        scroll(
            animate(blob1, {
                x: [0, -50],
                y: [0, 30],
                scale: [1, 1.1]
            }),
            { target: missionSection, offset: ["start end", "end start"] }
        );
    }

    if (missionSection && blob2) {
        scroll(
            animate(blob2, {
                x: [0, 50],
                y: [0, -30],
                scale: [1, 1.15]
            }),
            { target: missionSection, offset: ["start end", "end start"] }
        );
    }

    if (missionSection && blob3) {
        scroll(
            animate(blob3, {
                x: [0, 30],
                y: [0, -50],
                scale: [1, 1.2]
            }),
            { target: missionSection, offset: ["start end", "end start"] }
        );
    }

    // ========================================
    // ANIMACIONES PARA LA SECCIÓN DE FEATURES
    // ========================================

    const featuresSection = document.querySelector("#features");
    if (featuresSection) {

        // Animación del título principal
        const featuresTitle = document.querySelector(".features-title");
        if (featuresTitle) {
            scroll(
                animate(featuresTitle, {
                    opacity: [0, 1],
                    scale: [0.9, 1],
                    y: [30, 0]
                }),
                { target: featuresSection, offset: ["start end", "start center"] }
            );
        }

        // Animación del subtítulo
        const featuresSubtitle = document.querySelector(".features-subtitle");
        if (featuresSubtitle) {
            scroll(
                animate(featuresSubtitle, {
                    opacity: [0, 1],
                    y: [20, 0]
                }),
                { target: featuresSection, offset: ["start end", "start 0.6"] }
            );
        }

        // Animaciones de las tarjetas con efectos escalonados
        const featureCard1 = document.querySelector(".feature-card-1");
        const featureCard2 = document.querySelector(".feature-card-2");
        const featureCard3 = document.querySelector(".feature-card-3");

        if (featureCard1) {
            inView(featureCard1, () => {
                animate(
                    featureCard1,
                    {
                        opacity: [0, 1],
                        y: [100, 0],
                        rotateZ: [-5, 0]
                    },
                    { duration: 0.8, delay: 0.1 }
                );
            }, { amount: 0.2 });

            // Hover animation con bounce
            featureCard1.addEventListener('mouseenter', () => {
                animate(featureCard1, {
                    y: -16,
                    scale: 1.02
                }, { duration: 0.2, easing: "ease-out" });
            });

            featureCard1.addEventListener('mouseleave', () => {
                animate(featureCard1, {
                    y: 0,
                    scale: 1
                }, { duration: 0.15, easing: "ease-out" });
            });
        }

        if (featureCard2) {
            inView(featureCard2, () => {
                animate(
                    featureCard2,
                    {
                        opacity: [0, 1],
                        y: [120, 0],
                        scale: [0.95, 1]
                    },
                    { duration: 0.9, delay: 0.2 }
                );
            }, { amount: 0.2 });

            // Hover animation más dramática para la card central
            featureCard2.addEventListener('mouseenter', () => {
                animate(featureCard2, {
                    y: -20,
                    scale: 1.05,
                    rotateZ: 1
                }, { duration: 0.5, easing: [0.34, 1.56, 0.64, 1] });
            });

            featureCard2.addEventListener('mouseleave', () => {
                animate(featureCard2, {
                    y: 0,
                    scale: 1.05,
                    rotateZ: 0
                }, { duration: 0.4, easing: "ease-out" });
            });
        }

        if (featureCard3) {
            inView(featureCard3, () => {
                animate(
                    featureCard3,
                    {
                        opacity: [0, 1],
                        y: [100, 0],
                        rotateZ: [5, 0]
                    },
                    { duration: 0.8, delay: 0.3 }
                );
            }, { amount: 0.2 });

            // Hover animation con bounce
            featureCard3.addEventListener('mouseenter', () => {
                animate(featureCard3, {
                    y: -16,
                    scale: 1.02
                }, { duration: 0.4, easing: [0.34, 1.56, 0.64, 1] });
            });

            featureCard3.addEventListener('mouseleave', () => {
                animate(featureCard3, {
                    y: 0,
                    scale: 1
                }, { duration: 0.3, easing: "ease-out" });
            });
        }

        // Animación parallax sutil en scroll para las cards
        if (featureCard1) {
            scroll(
                animate(featureCard1, {
                    y: [0, -20]
                }),
                { target: featuresSection, offset: ["start end", "end start"] }
            );
        }

        if (featureCard2) {
            scroll(
                animate(featureCard2, {
                    y: [0, -30]
                }),
                { target: featuresSection, offset: ["start end", "end start"] }
            );
        }

        if (featureCard3) {
            scroll(
                animate(featureCard3, {
                    y: [0, -20]
                }),
                { target: featuresSection, offset: ["start end", "end start"] }
            );
        }

        // Animaciones para los íconos dentro de las cards
        const featureIcons = document.querySelectorAll(".feature-card-1 .w-24, .feature-card-2 .w-24, .feature-card-3 .w-24");
        featureIcons.forEach((icon, index) => {
            if (icon) {
                inView(icon, () => {
                    animate(
                        icon,
                        {
                            scale: [0, 1],
                            rotateZ: [0, 360]
                        },
                        { duration: 0.6, delay: 0.3 + (index * 0.1) }
                    );
                }, { amount: 0.5 });
            }
        });
    }

    // ========================================
    // ANIMACIONES PARA LA SECCIÓN DE TESTIMONIOS
    // ========================================

    const testimonialsSection = document.querySelector("#testimonials");
    if (testimonialsSection) {

        // Animación del título de testimonios
        const testimonialsTitle = document.querySelector(".testimonials-title");
        if (testimonialsTitle) {
            scroll(
                animate(testimonialsTitle, {
                    opacity: [0, 1],
                    scale: [0.95, 1],
                    y: [40, 0]
                }),
                { target: testimonialsSection, offset: ["start end", "start center"] }
            );
        }

        // Animación del subtítulo de testimonios
        const testimonialsSubtitle = document.querySelector(".testimonials-subtitle");
        if (testimonialsSubtitle) {
            scroll(
                animate(testimonialsSubtitle, {
                    opacity: [0, 1],
                    y: [25, 0]
                }),
                { target: testimonialsSection, offset: ["start end", "start 0.6"] }
            );
        }

        // Animaciones de las tarjetas de testimonios
        const testimonialCard1 = document.querySelector(".testimonial-card-1");
        const testimonialCard2 = document.querySelector(".testimonial-card-2");
        const testimonialCard3 = document.querySelector(".testimonial-card-3");

    if (testimonialCard1) {
        inView(testimonialCard1, () => {
            animate(
                testimonialCard1,
                {
                    opacity: [0, 1],
                    y: [80, 0],
                    rotateZ: [-8, 0]
                },
                { duration: 0.9, delay: 0.1 }
            );
        }, { amount: 0.3 });

        // Hover con bounce y rotación
        testimonialCard1.addEventListener('mouseenter', () => {
            animate(testimonialCard1, {
                y: -12,
                scale: 1.03,
                rotateZ: -1
            }, { duration: 0.5, easing: [0.34, 1.56, 0.64, 1] });
        });

        testimonialCard1.addEventListener('mouseleave', () => {
            animate(testimonialCard1, {
                y: 0,
                scale: 1,
                rotateZ: 0
            }, { duration: 0.4, easing: "ease-out" });
        });
    }

    if (testimonialCard2) {
        inView(testimonialCard2, () => {
            animate(
                testimonialCard2,
                {
                    opacity: [0, 1],
                    y: [100, 0],
                    scale: [0.9, 1]
                },
                { duration: 1, delay: 0.2 }
            );
        }, { amount: 0.3 });

        // Hover con efecto más dramático para la card central
        testimonialCard2.addEventListener('mouseenter', () => {
            animate(testimonialCard2, {
                y: -18,
                scale: 1.08,
                rotateZ: 2
            }, { duration: 0.6, easing: [0.34, 1.56, 0.64, 1] });
        });

        testimonialCard2.addEventListener('mouseleave', () => {
            animate(testimonialCard2, {
                y: 0,
                scale: 1.05,
                rotateZ: 0
            }, { duration: 0.5, easing: "ease-out" });
        });
    }

    if (testimonialCard3) {
        inView(testimonialCard3, () => {
            animate(
                testimonialCard3,
                {
                    opacity: [0, 1],
                    y: [80, 0],
                    rotateZ: [8, 0]
                },
                { duration: 0.9, delay: 0.3 }
            );
        }, { amount: 0.3 });

        // Hover con bounce y rotación
        testimonialCard3.addEventListener('mouseenter', () => {
            animate(testimonialCard3, {
                y: -12,
                scale: 1.03,
                rotateZ: 1
            }, { duration: 0.5, easing: [0.34, 1.56, 0.64, 1] });
        });

        testimonialCard3.addEventListener('mouseleave', () => {
            animate(testimonialCard3, {
                y: 0,
                scale: 1,
                rotateZ: 0
            }, { duration: 0.4, easing: "ease-out" });
        });
    }

        // Animación parallax para testimonios
        if (testimonialCard1) {
            scroll(
                animate(testimonialCard1, {
                    y: [0, -25]
                }),
                { target: testimonialsSection, offset: ["start end", "end start"] }
            );
        }

        if (testimonialCard2) {
            scroll(
                animate(testimonialCard2, {
                    y: [0, -35]
                }),
                { target: testimonialsSection, offset: ["start end", "end start"] }
            );
        }

        if (testimonialCard3) {
            scroll(
                animate(testimonialCard3, {
                    y: [0, -25]
                }),
                { target: testimonialsSection, offset: ["start end", "end start"] }
            );
        }

        // Animaciones para los avatares dentro de las cards de testimonios
        const testimonialAvatars = document.querySelectorAll(".testimonial-card-1 .w-20, .testimonial-card-2 .w-20, .testimonial-card-3 .w-20");
        testimonialAvatars.forEach((avatar, index) => {
            if (avatar && avatar.classList.contains('bg-gradient-to-br') || avatar.classList.contains('bg-white')) {
                inView(avatar, () => {
                    animate(
                        avatar,
                        {
                            scale: [0, 1],
                            rotateZ: [180, 0]
                        },
                        { duration: 0.7, delay: 0.4 + (index * 0.1) }
                    );
                }, { amount: 0.8 });
            }
        });

        // Animación para las estrellas de rating
        const ratingStars = document.querySelectorAll(".testimonial-card-1 .flex.gap-1 span, .testimonial-card-2 .flex.gap-1 span, .testimonial-card-3 .flex.gap-1 span");
        ratingStars.forEach((star, index) => {
            if (star && star.textContent === '⭐') {
                inView(star, () => {
                    animate(
                        star,
                        {
                            scale: [0, 1, 1.2, 1],
                            rotateZ: [0, 360]
                        },
                        { duration: 0.6, delay: 0.6 + (index * 0.05) }
                    );
                }, { amount: 1 });
            }
        });
    }

    // ========================================
    // CANDADO 3D CON THREE.JS PARA PRICING
    // ========================================

    const container = document.getElementById('lock-3d-container');
    if (!container) return;

    // Dynamically import Three.js
    import('three').then((THREE) => {
        // Scene setup
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(
            75,
            container.offsetWidth / container.offsetHeight,
            0.1,
            1000
        );

        const renderer = new THREE.WebGLRenderer({
            antialias: true,
            alpha: true
        });
        renderer.setSize(container.offsetWidth, container.offsetHeight);
        renderer.setPixelRatio(window.devicePixelRatio);
        container.appendChild(renderer.domElement);

        // Lighting
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
        scene.add(ambientLight);

        const directionalLight1 = new THREE.DirectionalLight(0x8b5cf6, 0.8);
        directionalLight1.position.set(5, 5, 5);
        scene.add(directionalLight1);

        const directionalLight2 = new THREE.DirectionalLight(0xc084fc, 0.5);
        directionalLight2.position.set(-5, -5, 5);
        scene.add(directionalLight2);

        // Create lock group
        const lockGroup = new THREE.Group();

        // Lock body
        const bodyGeometry = new THREE.BoxGeometry(2, 2.5, 0.8);
        const bodyMaterial = new THREE.MeshStandardMaterial({
            color: 0x8b5cf6,
            metalness: 0.7,
            roughness: 0.3
        });
        const lockBody = new THREE.Mesh(bodyGeometry, bodyMaterial);
        lockBody.position.y = -0.5;
        lockGroup.add(lockBody);

        // Shackle group (CERRADO - conectado al cuerpo)
        const shackleGroup = new THREE.Group();
        const shackleMaterial = new THREE.MeshStandardMaterial({
            color: 0x9333ea,
            metalness: 0.8,
            roughness: 0.2
        });

        // Left shackle (más corto y bajo, insertado en el cuerpo)
        const leftShackleGeometry = new THREE.CylinderGeometry(0.15, 0.15, 1.0, 16);
        const leftShackle = new THREE.Mesh(leftShackleGeometry, shackleMaterial);
        leftShackle.position.set(-0.7, 1.15, 0);
        shackleGroup.add(leftShackle);

        // Right shackle (más corto y bajo, insertado en el cuerpo)
        const rightShackle = leftShackle.clone();
        rightShackle.position.set(0.7, 1.15, 0);
        shackleGroup.add(rightShackle);

        // Top shackle (arco superior más bajo - candado CERRADO)
        const topShackleGeometry = new THREE.TorusGeometry(0.7, 0.15, 16, 32, Math.PI);
        const topShackle = new THREE.Mesh(topShackleGeometry, shackleMaterial);
        topShackle.rotation.z = Math.PI / 100;
        topShackle.position.y = 1.4;
        shackleGroup.add(topShackle);

        // Conectores que unen el shackle con el cuerpo (tapas inferiores)
        const connectorGeometry = new THREE.CylinderGeometry(0.18, 0.18, 0.3, 16);
        const leftConnector = new THREE.Mesh(connectorGeometry, bodyMaterial);
        leftConnector.position.set(-0.7, 0.7, 0);
        lockGroup.add(leftConnector);

        const rightConnector = new THREE.Mesh(connectorGeometry, bodyMaterial);
        rightConnector.position.set(0.7, 0.7, 0);
        lockGroup.add(rightConnector);

        lockGroup.add(shackleGroup);

        // Keyhole
        const keyholeGeometry = new THREE.CylinderGeometry(0.15, 0.05, 0.85, 16);
        const keyholeMaterial = new THREE.MeshStandardMaterial({
            color: 0x6b21a8,
            metalness: 0.9,
            roughness: 0.1
        });
        const keyhole = new THREE.Mesh(keyholeGeometry, keyholeMaterial);
        keyhole.rotation.x = Math.PI / 2;
        keyhole.position.set(0, -0.3, 0.41);
        lockGroup.add(keyhole);

        // Sparkles
        const sparklesGeometry = new THREE.BufferGeometry();
        const sparklesCount = 100;
        const positions = new Float32Array(sparklesCount * 3);
        for (let i = 0; i < sparklesCount * 3; i++) {
            positions[i] = (Math.random() - 0.5) * 8;
        }
        sparklesGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        const sparklesMaterial = new THREE.PointsMaterial({
            color: 0xc084fc,
            size: 0.05,
            transparent: true,
            opacity: 0.8
        });
        const sparkles = new THREE.Points(sparklesGeometry, sparklesMaterial);
        scene.add(sparkles);

        scene.add(lockGroup);
        camera.position.z = 6;

        // Animation loop
        let time = 0;
        function animate() {
            requestAnimationFrame(animate);
            time += 0.01;

            lockGroup.rotation.y = Math.sin(time * 0.5) * 0.3;
            lockGroup.rotation.x = Math.cos(time * 0.3) * 0.1;
            lockGroup.position.y = Math.sin(time) * 0.2;

            sparkles.rotation.y = time * 0.2;

            const scale = 1 + Math.sin(time * 2) * 0.05;
            shackleGroup.scale.set(1, scale, 1);

            renderer.render(scene, camera);
        }
        animate();

        // Handle resize
        window.addEventListener('resize', () => {
            const width = container.offsetWidth;
            const height = container.offsetHeight;
            camera.aspect = width / height;
            camera.updateProjectionMatrix();
            renderer.setSize(width, height);
        });
    }).catch(error => {
        console.error('Error loading Three.js:', error);
    });
});

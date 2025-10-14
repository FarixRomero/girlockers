import * as THREE from 'three';

export function initLock3D(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

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

    // Lock body (main rectangle)
    const bodyGeometry = new THREE.BoxGeometry(2, 2.5, 0.8);
    const bodyMaterial = new THREE.MeshStandardMaterial({
        color: 0x8b5cf6,
        metalness: 0.7,
        roughness: 0.3
    });
    const lockBody = new THREE.Mesh(bodyGeometry, bodyMaterial);
    lockBody.position.y = -0.5;
    lockGroup.add(lockBody);

    // Lock shackle (curved part)
    const shackleGroup = new THREE.Group();

    // Left side of shackle
    const leftShackleGeometry = new THREE.CylinderGeometry(0.15, 0.15, 2, 16);
    const shackleMaterial = new THREE.MeshStandardMaterial({
        color: 0x9333ea,
        metalness: 0.8,
        roughness: 0.2
    });
    const leftShackle = new THREE.Mesh(leftShackleGeometry, shackleMaterial);
    leftShackle.position.set(-0.7, 1.5, 0);
    shackleGroup.add(leftShackle);

    // Right side of shackle
    const rightShackle = leftShackle.clone();
    rightShackle.position.set(0.7, 1.5, 0);
    shackleGroup.add(rightShackle);

    // Top of shackle (curved)
    const topShackleGeometry = new THREE.TorusGeometry(0.7, 0.15, 16, 32, Math.PI);
    const topShackle = new THREE.Mesh(topShackleGeometry, shackleMaterial);
    topShackle.rotation.z = Math.PI / 2;
    topShackle.position.y = 2.5;
    shackleGroup.add(topShackle);

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

    // Add sparkles
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

    // Animation
    let time = 0;
    function animate() {
        requestAnimationFrame(animate);
        time += 0.01;

        // Rotate lock group
        lockGroup.rotation.y = Math.sin(time * 0.5) * 0.3;
        lockGroup.rotation.x = Math.cos(time * 0.3) * 0.1;

        // Float animation
        lockGroup.position.y = Math.sin(time) * 0.2;

        // Sparkles rotation
        sparkles.rotation.y = time * 0.2;

        // Pulse effect on shackle
        const scale = 1 + Math.sin(time * 2) * 0.05;
        shackleGroup.scale.set(1, scale, 1);

        renderer.render(scene, camera);
    }

    animate();

    // Handle resize
    function handleResize() {
        const width = container.offsetWidth;
        const height = container.offsetHeight;

        camera.aspect = width / height;
        camera.updateProjectionMatrix();
        renderer.setSize(width, height);
    }

    window.addEventListener('resize', handleResize);

    // Cleanup function
    return () => {
        window.removeEventListener('resize', handleResize);
        renderer.dispose();
        container.removeChild(renderer.domElement);
    };
}

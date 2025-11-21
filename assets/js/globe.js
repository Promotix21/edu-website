/**
 * EDU Career India - Three.js Globe
 * Interactive 3D rotating globe with country markers
 */

(function() {
  'use strict';

  // ========================================
  // THREE.JS GLOBE CONTROLLER
  // ========================================
  class GlobeController {
    constructor() {
      this.container = document.getElementById('globe-container');
      if (!this.container) return;

      // Check if Three.js is loaded
      if (typeof THREE === 'undefined') {
        console.warn('Three.js not loaded');
        return;
      }

      this.scene = null;
      this.camera = null;
      this.renderer = null;
      this.globe = null;
      this.markers = [];
      this.animationId = null;

      // Country locations (latitude, longitude)
      this.countries = [
        { name: 'india', lat: 20.5937, lon: 78.9629, color: 0xf59e0b },
        { name: 'usa', lat: 37.0902, lon: -95.7129, color: 0x2563eb },
        { name: 'uk', lat: 55.3781, lon: -3.4360, color: 0x10b981 },
        { name: 'australia', lat: -25.2744, lon: 133.7751, color: 0x8b5cf6 },
        { name: 'canada', lat: 56.1304, lon: -106.3468, color: 0xf59e0b },
        { name: 'dubai', lat: 25.2048, lon: 55.2708, color: 0x2563eb }
      ];

      this.init();
    }

    init() {
      this.createScene();
      this.createCamera();
      this.createRenderer();
      this.createLights();
      this.createGlobe();
      this.createMarkers();
      this.setupLocationTags();
      this.animate();
      this.handleResize();

      console.log('✓ Three.js globe initialized');
    }

    createScene() {
      this.scene = new THREE.Scene();
      this.scene.background = new THREE.Color(0x0a1628);
      this.scene.fog = new THREE.Fog(0x0a1628, 10, 50);
    }

    createCamera() {
      const aspect = this.container.clientWidth / this.container.clientHeight;
      this.camera = new THREE.PerspectiveCamera(50, aspect, 0.1, 1000);
      this.camera.position.z = 15;
    }

    createRenderer() {
      this.renderer = new THREE.WebGLRenderer({
        antialias: true,
        alpha: true
      });

      this.renderer.setSize(this.container.clientWidth, this.container.clientHeight);
      this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
      this.container.appendChild(this.renderer.domElement);
    }

    createLights() {
      // Ambient light
      const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
      this.scene.add(ambientLight);

      // Directional light
      const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
      directionalLight.position.set(5, 3, 5);
      this.scene.add(directionalLight);

      // Point light for glow effect
      const pointLight = new THREE.PointLight(0x2563eb, 1, 100);
      pointLight.position.set(0, 0, 10);
      this.scene.add(pointLight);
    }

    createGlobe() {
      // Create sphere geometry
      const geometry = new THREE.SphereGeometry(5, 64, 64);

      // Create material with earth texture
      // Note: Using simple color gradient since texture loading might fail
      const material = new THREE.MeshPhongMaterial({
        color: 0x2563eb,
        emissive: 0x112240,
        specular: 0x4488ff,
        shininess: 5,
        transparent: true,
        opacity: 0.9
      });

      this.globe = new THREE.Mesh(geometry, material);
      this.scene.add(this.globe);

      // Add wireframe overlay
      const wireframeGeometry = new THREE.SphereGeometry(5.05, 32, 32);
      const wireframeMaterial = new THREE.MeshBasicMaterial({
        color: 0x4488ff,
        wireframe: true,
        transparent: true,
        opacity: 0.1
      });

      const wireframe = new THREE.Mesh(wireframeGeometry, wireframeMaterial);
      this.scene.add(wireframe);

      // Add atmosphere glow
      const glowGeometry = new THREE.SphereGeometry(5.5, 32, 32);
      const glowMaterial = new THREE.ShaderMaterial({
        uniforms: {
          c: { type: "f", value: 0.5 },
          p: { type: "f", value: 4.5 }
        },
        vertexShader: `
          varying vec3 vNormal;
          void main() {
            vNormal = normalize(normalMatrix * normal);
            gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
          }
        `,
        fragmentShader: `
          uniform float c;
          uniform float p;
          varying vec3 vNormal;
          void main() {
            float intensity = pow(c - dot(vNormal, vec3(0.0, 0.0, 1.0)), p);
            gl_FragColor = vec4(0.15, 0.4, 0.9, 1.0) * intensity;
          }
        `,
        side: THREE.BackSide,
        blending: THREE.AdditiveBlending,
        transparent: true
      });

      const glow = new THREE.Mesh(glowGeometry, glowMaterial);
      this.scene.add(glow);
    }

    createMarkers() {
      this.countries.forEach(country => {
        // Convert lat/lon to 3D coordinates
        const phi = (90 - country.lat) * (Math.PI / 180);
        const theta = (country.lon + 180) * (Math.PI / 180);

        const x = -(5 * Math.sin(phi) * Math.cos(theta));
        const y = 5 * Math.cos(phi);
        const z = 5 * Math.sin(phi) * Math.sin(theta);

        // Create marker
        const markerGeometry = new THREE.SphereGeometry(0.1, 16, 16);
        const markerMaterial = new THREE.MeshBasicMaterial({
          color: country.color,
          transparent: true,
          opacity: 0.9
        });

        const marker = new THREE.Mesh(markerGeometry, markerMaterial);
        marker.position.set(x, y, z);
        marker.userData = { country: country.name };

        this.globe.add(marker);
        this.markers.push(marker);

        // Add marker pulse effect
        const pulseGeometry = new THREE.RingGeometry(0.15, 0.2, 32);
        const pulseMaterial = new THREE.MeshBasicMaterial({
          color: country.color,
          side: THREE.DoubleSide,
          transparent: true,
          opacity: 0.5
        });

        const pulse = new THREE.Mesh(pulseGeometry, pulseMaterial);
        pulse.position.set(x, y, z);
        pulse.lookAt(0, 0, 0);
        pulse.userData = { isPulse: true, initialOpacity: 0.5 };

        this.globe.add(pulse);
        this.markers.push(pulse);
      });
    }

    setupLocationTags() {
      const locationTags = document.querySelectorAll('.location-tag');

      locationTags.forEach(tag => {
        tag.addEventListener('click', () => {
          const countryName = tag.getAttribute('data-country');
          this.focusOnCountry(countryName);
        });
      });
    }

    focusOnCountry(countryName) {
      const country = this.countries.find(c => c.name === countryName);
      if (!country) return;

      // Calculate target rotation to center country
      const targetRotationY = -(country.lon * Math.PI / 180);
      const targetRotationX = (country.lat * Math.PI / 180);

      // Animate rotation with GSAP if available
      if (typeof gsap !== 'undefined') {
        gsap.to(this.globe.rotation, {
          y: targetRotationY,
          x: targetRotationX,
          duration: 1.5,
          ease: 'power2.inOut'
        });
      } else {
        this.globe.rotation.y = targetRotationY;
        this.globe.rotation.x = targetRotationX;
      }

      // Highlight marker
      this.markers.forEach(marker => {
        if (marker.userData.country === countryName) {
          const originalScale = marker.scale.clone();

          if (typeof gsap !== 'undefined') {
            gsap.fromTo(marker.scale,
              { x: 1, y: 1, z: 1 },
              {
                x: 2,
                y: 2,
                z: 2,
                duration: 0.5,
                yoyo: true,
                repeat: 1,
                ease: 'elastic.out(1, 0.3)'
              }
            );
          }
        }
      });
    }

    animate() {
      this.animationId = requestAnimationFrame(() => this.animate());

      // Rotate globe slowly
      this.globe.rotation.y += 0.002;

      // Animate marker pulses
      this.markers.forEach(marker => {
        if (marker.userData.isPulse) {
          marker.scale.x = 1 + Math.sin(Date.now() * 0.003) * 0.3;
          marker.scale.y = 1 + Math.sin(Date.now() * 0.003) * 0.3;
          marker.material.opacity = marker.userData.initialOpacity + Math.sin(Date.now() * 0.003) * 0.3;
        }
      });

      this.renderer.render(this.scene, this.camera);
    }

    handleResize() {
      window.addEventListener('resize', () => {
        // Update camera aspect ratio
        this.camera.aspect = this.container.clientWidth / this.container.clientHeight;
        this.camera.updateProjectionMatrix();

        // Update renderer size
        this.renderer.setSize(this.container.clientWidth, this.container.clientHeight);
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
      });
    }

    destroy() {
      if (this.animationId) {
        cancelAnimationFrame(this.animationId);
      }

      if (this.renderer) {
        this.renderer.dispose();
      }
    }
  }

  // ========================================
  // INITIALIZE ON DOM READY
  // ========================================
  function init() {
    // Small delay to ensure container is rendered
    setTimeout(() => {
      new GlobeController();
    }, 100);
  }

  // Wait for DOM to be ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();

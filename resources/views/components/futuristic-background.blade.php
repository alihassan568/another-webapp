<div class="fixed inset-0 z-0 pointer-events-none">
    <div class="matrix-rain" id="matrixRain-{{ $id ?? 'default' }}"></div>
    
    <div class="absolute inset-0 cyber-grid opacity-30"></div>
    
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-gradient-to-r from-cyan-400/20 to-purple-600/20 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-gradient-to-r from-purple-600/20 to-pink-600/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
    <div class="absolute top-1/2 right-1/3 w-64 h-64 bg-gradient-to-r from-pink-600/20 to-cyan-400/20 rounded-full blur-3xl animate-pulse delay-2000"></div>
</div>

<style>
    .matrix-rain {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        overflow: hidden;
    }
    
    .rain-drop {
        position: absolute;
        color: var(--neon-green, #00ff88);
        font-family: 'Courier New', monospace;
        font-size: 14px;
        animation: rain 3s linear infinite;
        opacity: 0.7;
    }
    
    @keyframes rain {
        0% { transform: translateY(-100vh); opacity: 1; }
        100% { transform: translateY(100vh); opacity: 0; }
    }
    
    .cyber-grid {
        background-image: 
            linear-gradient(rgba(0, 240, 255, 0.1) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 240, 255, 0.1) 1px, transparent 1px);
        background-size: 50px 50px;
    }
</style>

<script>
    function createMatrixRain(containerId) {
        const matrixRain = document.getElementById(containerId);
        if (!matrixRain) return;
        
        const chars = '01アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン';
        
        matrixRain.innerHTML = '';
        
        for (let i = 0; i < 50; i++) {
            const drop = document.createElement('div');
            drop.className = 'rain-drop';
            drop.style.left = Math.random() * 100 + '%';
            drop.style.animationDelay = Math.random() * 3 + 's';
            drop.style.animationDuration = (Math.random() * 3 + 2) + 's';
            drop.textContent = chars[Math.floor(Math.random() * chars.length)];
            matrixRain.appendChild(drop);
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const containerId = 'matrixRain-{{ $id ?? "default" }}';
        createMatrixRain(containerId);
        
        setInterval(() => {
            createMatrixRain(containerId);
        }, 5000);
    });
</script>
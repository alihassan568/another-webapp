<!-- Futuristic Styles Component -->
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Custom Futuristic Styles -->
<style>
    :root {
        --neon-blue: #00f0ff;
        --neon-purple: #8b5cf6;
        --neon-pink: #ff0080;
        --neon-green: #00ff88;
    }
    
    .font-orbitron { font-family: 'Orbitron', monospace; }
    .font-exo { font-family: 'Exo 2', sans-serif; }
    
    .neon-text {
        text-shadow: 0 0 10px var(--neon-blue), 0 0 20px var(--neon-blue), 0 0 30px var(--neon-blue);
    }
    
    .neon-border {
        border: 2px solid transparent;
        background: linear-gradient(45deg, var(--neon-blue), var(--neon-purple), var(--neon-pink)) border-box;
        -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: subtract;
        mask-composite: subtract;
    }
    
    .cyber-grid {
        background-image: 
            linear-gradient(rgba(0, 240, 255, 0.1) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 240, 255, 0.1) 1px, transparent 1px);
        background-size: 50px 50px;
    }
    
    .holographic {
        background: linear-gradient(45deg, 
            rgba(0, 240, 255, 0.1), 
            rgba(139, 92, 246, 0.1), 
            rgba(255, 0, 128, 0.1),
            rgba(0, 255, 136, 0.1));
        backdrop-filter: blur(10px);
    }
    
    .pulse-glow {
        animation: pulseGlow 2s infinite;
    }
    
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(0, 240, 255, 0.3); }
        50% { box-shadow: 0 0 40px rgba(0, 240, 255, 0.6), 0 0 60px rgba(139, 92, 246, 0.4); }
    }
    
    .floating {
        animation: floating 3s ease-in-out infinite;
    }
    
    @keyframes floating {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    
    .item-card {
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(0, 240, 255, 0.3);
        transition: all 0.3s ease;
    }
    
    .item-card:hover {
        border-color: var(--neon-blue);
        box-shadow: 0 10px 40px rgba(0, 240, 255, 0.3);
        transform: translateY(-5px);
    }
    
    .cyber-button {
        background: linear-gradient(45deg, rgba(0, 240, 255, 0.2), rgba(139, 92, 246, 0.2));
        border: 1px solid rgba(0, 240, 255, 0.5);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    
    .cyber-button:hover {
        background: linear-gradient(45deg, rgba(0, 240, 255, 0.3), rgba(139, 92, 246, 0.3));
        border-color: var(--neon-blue);
        box-shadow: 0 0 20px rgba(0, 240, 255, 0.4);
        transform: translateY(-2px);
    }
    
    .cyber-card {
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(0, 240, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .cyber-card:hover {
        border-color: rgba(0, 240, 255, 0.5);
        box-shadow: 0 8px 32px rgba(0, 240, 255, 0.15);
    }
    
    .glow-text {
        text-shadow: 0 0 10px currentColor, 0 0 20px currentColor, 0 0 30px currentColor;
    }
    
    /* Animated gradient backgrounds */
    .animated-gradient {
        background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
    }
    
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .cyber-input {
        background: rgba(0, 0, 0, 0.6);
        border: 1px solid rgba(0, 240, 255, 0.3);
        backdrop-filter: blur(10px);
        color: white;
        transition: all 0.3s ease;
    }
    
    .cyber-input:focus {
        border-color: var(--neon-blue);
        box-shadow: 0 0 0 3px rgba(0, 240, 255, 0.1), inset 0 0 10px rgba(0, 240, 255, 0.1);
        outline: none;
    }
    
    .cyber-input::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laracon - The Movie, The Game - Help Taylor Find His Lambo</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap');

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Orbitron', monospace;
            background:
                radial-gradient(circle at 25% 25%, #ff00ff 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, #00ffff 0%, transparent 50%),
                linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
            height: 100vh;
            overflow: hidden;
            position: relative;
            box-sizing: border-box;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 2px,
                    rgba(0, 255, 255, 0.03) 2px,
                    rgba(0, 255, 255, 0.03) 4px
                );
            pointer-events: none;
            z-index: 1;
        }

        .game-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: row;
            position: relative;
            z-index: 2;
        }

        .vs-panel {
            width: 30%;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.9), rgba(26, 26, 46, 0.9));
            border-right: 3px solid #00ffff;
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .vs-panel.visible {
            display: flex;
        }

        .vs-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(255, 0, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(0, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(255, 255, 0, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .game-area {
            width: 70%;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .game-area.full-width {
            width: 100%;
        }

        .hud {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .neon-text {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow:
                0 0 5px currentColor,
                0 0 10px currentColor,
                0 0 15px currentColor,
                0 0 20px currentColor;
            animation: neonPulse 2s ease-in-out infinite alternate;
        }

        @keyframes neonPulse {
            from { opacity: 0.8; }
            to { opacity: 1; }
        }

        .vs-horizontal {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
            width: 100%;
        }

        .vs-text-simple {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 24px;
            color: #ff00ff;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 0 0 10px #ff00ff;
        }

        .vs-face {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .vs-face img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid;
            box-shadow: 0 0 15px;
        }

        .vs-face.taylor img {
            border-color: #00ffff;
            box-shadow: 0 0 15px #00ffff;
        }

        .vs-face.villain img {
            border-color: #ff00ff;
            box-shadow: 0 0 15px #ff00ff;
        }

        .vs-name {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 0 0 8px;
        }

        .vs-face.taylor .vs-name {
            color: #00ffff;
            text-shadow: 0 0 8px #00ffff;
        }

        .vs-face.villain .vs-name {
            color: #ff00ff;
            text-shadow: 0 0 8px #ff00ff;
        }

        .level-display {
            color: #00ffff;
            font-size: 24px;
            background: rgba(0, 0, 0, 0.8);
            padding: 10px 20px;
            border: 2px solid #00ffff;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .time-display {
            color: #ff00ff;
            font-size: 18px;
            background: rgba(0, 0, 0, 0.8);
            padding: 8px 16px;
            border: 2px solid #ff00ff;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        .maze-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
            width: 100%;
            height: 100%;
            overflow: hidden;
            padding: 20px;
            box-sizing: border-box;
        }

        .maze {
            display: grid;
            gap: 2px;
            padding: 20px;
            border-radius: 15px;
            background: rgba(0, 0, 0, 0.8);
            border: 3px solid var(--level-color, #00ffff);
            backdrop-filter: blur(10px);
            box-shadow:
                0 0 20px var(--level-color, #00ffff),
                inset 0 0 20px rgba(0, 0, 0, 0.5);
            max-width: 90vw;
            max-height: 90vh;
            box-sizing: border-box;
        }

        .cell {
            width: 32px;
            height: 32px;
            border-radius: 4px;
            position: relative;
            transition: all 0.3s ease;
        }

        @media (max-width: 1200px) {
            .cell {
                width: 28px;
                height: 28px;
            }
        }

        @media (max-width: 900px) {
            .cell {
                width: 24px;
                height: 24px;
            }
        }

        @media (max-width: 700px) {
            .cell {
                width: 20px;
                height: 20px;
            }
        }

        .wall {
            background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
            border: 1px solid var(--level-color, #00ffff);
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.8);
        }

        .path {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Level-specific color themes */
        .level-1 { --level-color: #00ffff; }
        .level-2 { --level-color: #ff00ff; }
        .level-3 { --level-color: #ffff00; }
        .level-4 { --level-color: #ff8000; }
        .level-5 { --level-color: #8000ff; }

        /* Progressive background difficulty themes */
        body.level-1 {
            background:
                radial-gradient(circle at 25% 25%, #ff00ff 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, #00ffff 0%, transparent 50%),
                linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
        }

        body.level-2 {
            background:
                radial-gradient(circle at 25% 25%, #ff00ff 0%, transparent 40%),
                radial-gradient(circle at 75% 75%, #00ffff 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, #ff0040 0%, transparent 60%),
                linear-gradient(135deg, #0d0a0d 0%, #2a1a2a 50%, #2e1640 100%);
        }

        body.level-3 {
            background:
                radial-gradient(circle at 20% 30%, #ffff00 0%, transparent 35%),
                radial-gradient(circle at 80% 70%, #ff00ff 0%, transparent 35%),
                radial-gradient(circle at 50% 10%, #00ffff 0%, transparent 45%),
                linear-gradient(135deg, #0f0a0a 0%, #2e2a1a 50%, #403016 100%);
        }

        body.level-4 {
            background:
                radial-gradient(circle at 15% 25%, #ff8000 0%, transparent 30%),
                radial-gradient(circle at 85% 75%, #ff0040 0%, transparent 30%),
                radial-gradient(circle at 50% 50%, #8000ff 0%, transparent 40%),
                radial-gradient(circle at 30% 80%, #ffff00 0%, transparent 35%),
                linear-gradient(135deg, #120a0a 0%, #3a1a1a 50%, #4a1616 100%);
        }

        body.level-5 {
            background:
                radial-gradient(circle at 10% 20%, #8000ff 0%, transparent 25%),
                radial-gradient(circle at 90% 80%, #ff0040 0%, transparent 25%),
                radial-gradient(circle at 50% 10%, #ff8000 0%, transparent 30%),
                radial-gradient(circle at 20% 90%, #00ffff 0%, transparent 25%),
                radial-gradient(circle at 80% 30%, #ffff00 0%, transparent 25%),
                linear-gradient(135deg, #1a0a0a 0%, #4a1a1a 50%, #601616 100%);
        }

        .sprite {
            width: 100%;
            height: 100%;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            position: absolute;
            top: 0;
            left: 0;
            border-radius: 4px;
        }

        .taylor-sprite {
            background-image: url('/images/taylor.jpg');
            box-shadow:
                0 0 15px #00ffff,
                0 0 30px #00ffff,
                inset 0 0 15px rgba(0, 255, 255, 0.2);
            border: 2px solid #00ffff;
            animation: playerGlow 1.5s ease-in-out infinite alternate;
        }

        @keyframes playerGlow {
            from { box-shadow: 0 0 15px #00ffff, 0 0 30px #00ffff; }
            to { box-shadow: 0 0 25px #00ffff, 0 0 50px #00ffff; }
        }

        .taylor-sprite::after {
            content: '👨';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            text-shadow: 0 0 10px #00ffff;
            display: none;
        }

        .lambo-sprite {
            background-image: url('/images/lambo.jpg');
            box-shadow:
                0 0 20px #ffff00,
                0 0 40px #ffff00,
                inset 0 0 20px rgba(255, 255, 0, 0.3);
            border: 2px solid #ffff00;
            animation: goalPulse 1s ease-in-out infinite;
        }

        @keyframes goalPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .lambo-sprite::after {
            content: '🚗';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            text-shadow: 0 0 10px #ffff00;
            display: none;
        }

        .taylor-sprite.fallback::after,
        .lambo-sprite.fallback::after {
            display: block;
        }

        .villain-sprite {
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            box-shadow:
                0 0 15px #ff0040,
                0 0 30px #ff0040,
                inset 0 0 15px rgba(255, 0, 64, 0.3);
            border: 2px solid #ff0040;
            border-radius: 4px;
            animation: villainFlicker 0.5s ease-in-out infinite alternate;
            transition: all 0.3s ease-out;
        }

        @keyframes villainFlicker {
            from { opacity: 0.8; }
            to { opacity: 1; }
        }

        .villain-sprite::after {
            content: '👹';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            text-shadow: 0 0 10px #ff0040;
            display: block;
        }

        .villain-sprite.has-image::after {
            display: none;
        }

        .game-title {
            position: absolute;
            top: 35%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 48px;
            color: #00ffff;
            text-transform: uppercase;
            letter-spacing: 4px;
            text-shadow:
                0 0 10px #00ffff,
                0 0 20px #00ffff,
                0 0 30px #00ffff,
                0 0 40px #00ffff;
            animation: titlePulse 3s ease-in-out infinite;
            text-align: center;
            z-index: 15;
        }

        .game-logo {
            position: absolute;
            top: 35%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 85vw;
            max-height: 50vh;
            width: auto;
            height: auto;
            filter:
                drop-shadow(0 0 30px #00ffff)
                drop-shadow(0 0 60px #00ffff)
                drop-shadow(0 0 90px #00ffff);
            animation: titlePulse 3s ease-in-out infinite;
            z-index: 15;
        }


        @keyframes titlePulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.05); }
        }

        .text-zone {
            position: absolute;
            bottom: 20%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            padding: 30px 40px;
            border-radius: 15px;
            backdrop-filter: blur(12px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow:
                0 0 30px rgba(0, 0, 0, 0.5),
                inset 0 0 20px rgba(255, 255, 255, 0.05);
            max-width: 80%;
            text-align: center;
            z-index: 15;
            line-height: 1.4;
        }

        .game-tagline {
            font-family: 'Orbitron', monospace;
            font-weight: 400;
            font-size: 16px;
            color: #ff00ff;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-shadow: 0 0 8px #ff00ff, 0 0 16px #ff00ff;
            margin-bottom: 25px;
        }

        .start-prompt {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            font-size: 20px;
            color: #00ffff;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow:
                0 0 5px #00ffff,
                0 0 10px #00ffff,
                0 0 15px #00ffff;
            animation: promptBlink 1.5s ease-in-out infinite;
            margin-bottom: 15px;
        }

        .leaderboard-prompt {
            font-family: 'Orbitron', monospace;
            font-weight: 400;
            font-size: 14px;
            color: #ffff00;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 0 0 5px #ffff00;
        }

        @keyframes promptBlink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.3; }
        }

        .game-over {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.9), rgba(26, 26, 46, 0.9));
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            z-index: 20;
            display: none;
            border: 3px solid #00ffff;
            backdrop-filter: blur(15px);
            box-shadow:
                0 0 30px #00ffff,
                0 0 60px #00ffff,
                inset 0 0 30px rgba(0, 255, 255, 0.1);
        }

        .game-over h2 {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 36px;
            color: #00ffff;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin: 0 0 20px 0;
            text-shadow:
                0 0 10px #00ffff,
                0 0 20px #00ffff,
                0 0 30px #00ffff;
            animation: neonPulse 2s ease-in-out infinite alternate;
        }

        .game-over p {
            font-family: 'Orbitron', monospace;
            font-weight: 400;
            font-size: 18px;
            color: #ff00ff;
            margin: 15px 0;
            text-shadow: 0 0 10px #ff00ff;
        }

        .neon-button {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 15px 30px;
            margin: 10px;
            background: transparent;
            border: 2px solid #ff00ff;
            color: #ff00ff;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-shadow: 0 0 5px currentColor;
            box-shadow: 0 0 10px currentColor;
        }

        .neon-button:hover {
            background: rgba(255, 0, 255, 0.1);
            box-shadow:
                0 0 20px currentColor,
                0 0 40px currentColor,
                inset 0 0 20px rgba(255, 0, 255, 0.1);
            transform: translateY(-2px);
        }

        .main-menu {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 50;
        }

        .name-prompt {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .leaderboard-entry {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 25px;
            margin: 12px 0;
            background: rgba(0, 255, 255, 0.06);
            border: 2px solid rgba(0, 255, 255, 0.3);
            border-radius: 12px;
            font-family: 'Orbitron', monospace;
            transition: all 0.3s ease;
            position: relative;
            min-height: 70px;
        }

        .leaderboard-entry:hover {
            background: rgba(0, 255, 255, 0.1);
            border-color: rgba(0, 255, 255, 0.5);
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
        }

        .leaderboard-entry.podium {
            border-width: 3px;
            box-shadow: 0 0 25px currentColor;
            transform: scale(1.02);
            margin: 15px 0;
            padding: 25px 30px;
            min-height: 80px;
        }

        .leaderboard-entry.first {
            border-color: #FFD700;
            background: rgba(255, 215, 0, 0.15);
            color: #FFD700;
            box-shadow: 0 0 25px #FFD700, inset 0 0 20px rgba(255, 215, 0, 0.1);
        }

        .leaderboard-entry.second {
            border-color: #C0C0C0;
            background: rgba(192, 192, 192, 0.15);
            color: #C0C0C0;
            box-shadow: 0 0 20px #C0C0C0, inset 0 0 15px rgba(192, 192, 192, 0.1);
        }

        .leaderboard-entry.third {
            border-color: #CD7F32;
            background: rgba(205, 127, 50, 0.15);
            color: #CD7F32;
            box-shadow: 0 0 15px #CD7F32, inset 0 0 10px rgba(205, 127, 50, 0.1);
        }

        .leaderboard-rank {
            font-weight: 900;
            font-size: 26px;
            min-width: 80px;
            text-align: center;
            text-shadow: 0 0 10px currentColor;
        }

        .leaderboard-name {
            flex: 1;
            font-size: 20px;
            font-weight: 700;
            margin-left: 20px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .leaderboard-player-name {
            font-size: 18px;
            font-weight: 700;
        }

        .leaderboard-github {
            font-size: 14px;
            opacity: 0.9;
        }

        .leaderboard-github a {
            color: #00ffff;
            text-decoration: none;
            opacity: 0.85;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 255, 255, 0.4);
            padding: 4px 10px;
            border-radius: 6px;
            background: rgba(0, 255, 255, 0.08);
            display: inline-block;
            font-weight: 500;
        }

        .leaderboard-github a:hover {
            opacity: 1;
            text-shadow: 0 0 10px #00ffff;
            border-color: rgba(0, 255, 255, 0.7);
            background: rgba(0, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 255, 255, 0.3);
        }

        .leaderboard-time {
            font-weight: 900;
            font-size: 20px;
            text-shadow: 0 0 10px currentColor;
            min-width: 90px;
            text-align: right;
        }

        .audio-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: rgba(0, 0, 0, 0.8);
            border: 2px solid #00ffff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            box-shadow:
                0 0 15px rgba(0, 255, 255, 0.3),
                inset 0 0 15px rgba(0, 0, 0, 0.3);
        }

        .audio-toggle:hover {
            border-color: #ff00ff;
            box-shadow:
                0 0 25px rgba(255, 0, 255, 0.5),
                inset 0 0 20px rgba(0, 0, 0, 0.4);
            transform: scale(1.05);
        }

        .audio-toggle.muted {
            border-color: #ff4444;
            box-shadow:
                0 0 15px rgba(255, 68, 68, 0.3),
                inset 0 0 15px rgba(0, 0, 0, 0.3);
        }

        .audio-toggle.muted:hover {
            border-color: #ff6666;
            box-shadow:
                0 0 25px rgba(255, 102, 102, 0.5),
                inset 0 0 20px rgba(0, 0, 0, 0.4);
        }

        .audio-icon {
            font-size: 24px;
            color: #00ffff;
            text-shadow: 0 0 10px currentColor;
            transition: all 0.3s ease;
        }

        .audio-toggle:hover .audio-icon {
            color: #ff00ff;
            text-shadow: 0 0 15px #ff00ff;
        }

        .audio-toggle.muted .audio-icon {
            color: #ff4444;
            text-shadow: 0 0 10px #ff4444;
        }

        .audio-toggle.muted:hover .audio-icon {
            color: #ff6666;
            text-shadow: 0 0 15px #ff6666;
        }

        .name-prompt-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .name-input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 8px;
            margin: 15px 0;
            box-sizing: border-box;
        }

        .name-input:focus {
            outline: none;
            border-color: #007bff;
        }

        .welcome-text {
            color: #666;
            margin-bottom: 15px;
        }

        .leaderboard {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255,255,255,0.9);
            padding: 10px;
            border-radius: 8px;
            font-size: 12px;
            z-index: 10;
            max-width: 200px;
        }

        .leaderboard h3 {
            margin: 0 0 8px 0;
            font-size: 14px;
            text-align: center;
        }

        .leaderboard-entry {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <!-- Background Music -->
    <audio id="background-music" src="/song.wav" loop preload="auto"></audio>

    <!-- Audio Toggle Button -->
    <div id="audio-toggle" class="audio-toggle" onclick="toggleAudio()">
        <div class="audio-icon" id="audio-icon">🔊</div>
    </div>
    <div id="name-prompt" class="name-prompt">
        <div class="name-prompt-content">
            <h2>🎮 Welcome to the Game!</h2>
            <p class="welcome-text">Enter your details to start playing and compete on the leaderboard!</p>
            <input type="text" id="player-name-input" class="name-input" placeholder="Enter your name..." maxlength="20">
            <input type="text" id="player-github-input" class="name-input" placeholder="GitHub username (optional)" maxlength="50">
            <p class="welcome-text" style="font-size: 12px; color: #888; margin-top: 5px;">Add your GitHub to get followers from other players!</p>
            <br>
            <button class="btn btn-primary" onclick="setPlayerName()">Start Playing!</button>
        </div>
    </div>

    <div class="game-container">
        <!-- VS Panel -->
        <div class="vs-panel" id="vs-panel">
            <div class="vs-content">
                <div style="position: absolute; top: 20px; left: 20px; display: flex; gap: 10px;">
                    <div class="level-display neon-text" style="font-size: 18px; color: #00ffff; background: rgba(0, 0, 0, 0.8); padding: 8px 16px; border: 2px solid #00ffff; border-radius: 8px; backdrop-filter: blur(10px);">
                        LEVEL <span id="current-level">1</span> / 5
                    </div>
                    <div class="time-display neon-text" style="font-size: 18px; color: #ff00ff; background: rgba(0, 0, 0, 0.8); padding: 8px 16px; border: 2px solid #ff00ff; border-radius: 8px; backdrop-filter: blur(10px);">
                        <span id="total-time">0.0s</span>
                    </div>
                </div>

                <div class="vs-horizontal">
                    <div class="vs-face taylor">
                        <img src="/images/taylor.jpg" alt="Taylor">
                        <div class="vs-name">TAYLOR</div>
                    </div>

                    <div class="vs-text-simple">VS</div>

                    <div class="vs-face villain">
                        <img id="villain-image" src="" alt="Villain">
                        <div class="vs-name" id="villain-name-short"></div>
                    </div>
                </div>

                <div class="villain-info" style="text-align: center; margin-top: 20px;">
                    <div class="villain-title" id="villain-title" style="font-size: 16px; color: #00ffff; margin-bottom: 8px; text-shadow: 0 0 10px #00ffff;">
                    </div>
                    <div class="villain-tagline" id="villain-tagline" style="font-size: 14px; color: #ffff00; font-style: italic; text-shadow: 0 0 8px #ffff00;">
                    </div>
                </div>

            </div>
        </div>

        <!-- Game Area -->
        <div class="game-area full-width">

        <!-- Main Menu Overlay -->
        <div id="main-menu" class="main-menu">
            <div id="title-container">
                <img id="game-logo" class="game-logo" src="/images/logo.png" alt="Game Logo" style="display: none;">
                <div id="game-title" class="game-title neon-text" style="display: block;">
                    🚗 HELP TAYLOR<br>FIND HIS LAMBO 🏎️
                </div>
            </div>

            <div class="text-zone">
                <div class="game-tagline neon-text">
                    Taylor's Lambo has been stolen!<br>
                    Navigate the neon mazes at lightning speed<br>
                    to escape the villains and reclaim his ride!
                </div>
                <div class="start-prompt neon-text">
                    Press ENTER to begin
                </div>
                <div class="leaderboard-prompt neon-text">
                    Press ESC or TAB for Leaderboard
                </div>
            </div>
        </div>

        <!-- Leaderboard Overlay -->
        <div id="leaderboard-overlay" class="main-menu" style="display: none;">
            <div style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.9), rgba(26, 26, 46, 0.9)); padding: 50px; border-radius: 20px; border: 3px solid #00ffff; backdrop-filter: blur(15px); box-shadow: 0 0 30px #00ffff, 0 0 60px #00ffff, inset 0 0 30px rgba(0, 255, 255, 0.1); max-width: 900px; width: 95%; max-height: 85vh; overflow-y: auto;">
                <h2 class="neon-text" style="text-align: center; margin-bottom: 30px; font-size: 36px;">🏆 LEADERBOARD</h2>
                <div id="leaderboard-content"></div>
                <div style="text-align: center; margin-top: 30px;">
                    <button class="neon-button" onclick="hideLeaderboard()">← Back to Menu</button>
                </div>
            </div>
        </div>

        <!-- Game Over Screen -->
        <div class="game-over" id="game-over">
            <h2>MISSION COMPLETE</h2>
            <p>Final Time: <span id="final-time"></span></p>
            <p>Score submitted to the grid!</p>
            <button class="neon-button" onclick="resetGame()">Play Again</button>
        </div>

            <!-- Game Area -->
            <div class="maze-container">
                <div id="maze" class="maze level-1"></div>
            </div>
        </div>
    </div>

    <script>
        let playerName = '';
        let playerGitHub = '';
        let backgroundMusic = null;
        let musicEnabled = true;

        function setCookie(name, value, days) {
            const expires = new Date();
            expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
        }

        function getCookie(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        function initializeAudio() {
            backgroundMusic = document.getElementById('background-music');
            const savedMusicState = getCookie('musicEnabled');
            musicEnabled = savedMusicState !== 'false'; // Default to true unless explicitly disabled

            if (backgroundMusic) {
                backgroundMusic.volume = 0.3; // Set volume to 30%
                // Start paused to avoid auto-play issues
                backgroundMusic.pause();
                updateAudioButton();
            }
        }

        function toggleAudio() {
            if (!backgroundMusic) return;

            musicEnabled = !musicEnabled;
            setCookie('musicEnabled', musicEnabled, 30);

            if (musicEnabled) {
                setTimeout(() => {
                    backgroundMusic.play().catch(() => {
                        console.log('Audio play failed');
                    });
                }, 500); // Shorter delay for manual toggle
            } else {
                backgroundMusic.pause();
            }

            updateAudioButton();
        }

        function updateAudioButton() {
            const toggleButton = document.getElementById('audio-toggle');
            const audioIcon = document.getElementById('audio-icon');

            if (musicEnabled) {
                toggleButton.classList.remove('muted');
                audioIcon.textContent = '🔊';
            } else {
                toggleButton.classList.add('muted');
                audioIcon.textContent = '🔇';
            }
        }

        // Try to resume audio on user interaction with delay
        function resumeAudioContext() {
            if (backgroundMusic && musicEnabled && backgroundMusic.paused) {
                setTimeout(() => {
                    backgroundMusic.play().catch(() => {
                        console.log('Audio playback failed after delay');
                    });
                }, 1000); // 1 second delay
            }
        }

        function setPlayerName() {
            const nameInput = document.getElementById('player-name-input');
            const githubInput = document.getElementById('player-github-input');
            const name = nameInput.value.trim();
            const github = githubInput ? githubInput.value.trim() : '';

            if (name && name.length > 0) {
                playerName = name;
                playerGitHub = github;
                setCookie('playerName', playerName, 30);
                setCookie('playerGitHub', playerGitHub, 30);
                document.getElementById('name-prompt').style.display = 'none';
                updateWelcomeMessage();

                // Resume audio on user interaction
                resumeAudioContext();
            } else {
                alert('Please enter your name to continue!');
                nameInput.focus();
            }
        }

        function updateWelcomeMessage() {
            const titleElement = document.querySelector('.game-title');
            titleElement.innerHTML = `🚗 Help Taylor Find His Lambo, ${playerName}! 🏎️`;
        }

        async function checkPlayerName() {
            const savedName = getCookie('playerName');
            const savedGitHub = getCookie('playerGitHub');
            if (savedName) {
                playerName = savedName;
                playerGitHub = savedGitHub || '';
                await showWelcomeBack();
            } else {
                showNamePrompt();
            }
        }

        function showNamePrompt() {
            document.getElementById('name-prompt').style.display = 'flex';
            setTimeout(() => {
                document.getElementById('player-name-input').focus();
            }, 100);
        }

        async function showWelcomeBack() {
            try {
                const response = await fetch('/leaderboard');
                const scores = await response.json();

                const playerScore = scores.find(score => score.player_name === playerName);
                const promptContent = document.querySelector('.name-prompt-content');

                if (playerScore) {
                    const playerRank = scores.findIndex(score => score.player_name === playerName) + 1;
                    promptContent.innerHTML = `
                        <h2>🎉 Welcome back, ${playerName}!</h2>
                        <p class="welcome-text">Your best time: <strong>${playerScore.total_time}s</strong></p>
                        <p class="welcome-text">You are <strong>#${playerRank}</strong> on the leaderboard!</p>
                        <button class="btn btn-primary" onclick="startNewGame()">Play Again</button>
                        <button class="btn btn-success" onclick="changePlayerName()">Change Name</button>
                    `;
                } else {
                    promptContent.innerHTML = `
                        <h2>👋 Welcome back, ${playerName}!</h2>
                        <p class="welcome-text">You don't have a best time yet.</p>
                        <p class="welcome-text">Set your best time and get on the leaderboard!</p>
                        <button class="btn btn-primary" onclick="startNewGame()">Set Best Time</button>
                        <button class="btn btn-success" onclick="changePlayerName()">Change Name</button>
                    `;
                }

                document.getElementById('name-prompt').style.display = 'flex';
            } catch (error) {
                console.error('Error checking player stats:', error);
                document.getElementById('name-prompt').style.display = 'none';
                updateWelcomeMessage();
            }
        }

        function startNewGame() {
            document.getElementById('name-prompt').style.display = 'none';
            updateWelcomeMessage();
        }

        function changePlayerName() {
            const promptContent = document.querySelector('.name-prompt-content');
            promptContent.innerHTML = `
                <h2>🎮 Change Your Details</h2>
                <p class="welcome-text">Update your name and GitHub username!</p>
                <input type="text" id="player-name-input" class="name-input" placeholder="Enter your name..." maxlength="20" value="${playerName}">
                <input type="text" id="player-github-input" class="name-input" placeholder="GitHub username (optional)" maxlength="50" value="${playerGitHub}">
                <p class="welcome-text" style="font-size: 12px; color: #888; margin-top: 5px;">Add your GitHub to get followers from other players!</p>
                <br>
                <button class="btn btn-primary" onclick="setPlayerName()">Update Details</button>
            `;
            setTimeout(() => {
                document.getElementById('player-name-input').focus();
                document.getElementById('player-name-input').select();
            }, 100);
        }

        let gameState = {
            level: 1,
            playerX: 2,
            playerY: 2,
            maze: [],
            villains: [],
            gameStarted: false,
            startTime: null,
            currentLevelStartTime: null,
            totalTime: 0,
            isPlaying: false
        };

        let availableVillainCount = 5;

        const villainData = {
            1: {
                name: "NUNO MADURO",
                title: "The Coverage Animal",
                tagline: "He sees everything… and tests it twice.",
                image: "nuno.png"
            },
            2: {
                name: "CALEB PORZIO",
                title: "The Reactive Renegade",
                tagline: "Wires minds together… then melts them apart.",
                image: "caleb.png"
            },
            3: {
                name: "ADAM WATHAN",
                title: "The Utility Overlord",
                tagline: "He'll refactor your soul—one class at a time.",
                image: "adam.png"
            },
            4: {
                name: "AARON FRANCIS",
                title: "The Metrics Mastermind",
                tagline: "He tracks every move… before you make it.",
                image: "aaron.png"
            },
            5: {
                name: "FREEK VAN DER HERTEN",
                title: "The Magician of Middleware",
                tagline: "He logs in without credentials… and logs you out permanently.",
                image: "freek.png"
            }
        };

        let gameTimer;
        let villainTimer;

        function startGame() {
            document.getElementById('main-menu').style.display = 'none';
            document.getElementById('vs-panel').classList.add('visible');
            document.querySelector('.game-area').classList.remove('full-width');
            gameState.gameStarted = true;
            gameState.isPlaying = true;
            gameState.level = 1;
            gameState.totalTime = 0;
            gameState.startTime = Date.now();
            gameState.currentLevelStartTime = Date.now();

            document.getElementById('game-over').style.display = 'none';

            // Resume audio on game start (user interaction)
            resumeAudioContext();

            loadLevel(1);
            startTimer();
        }

        function resetGame() {
            gameState = {
                level: 1,
                playerX: 2,
                playerY: 2,
                maze: [],
                villains: [],
                gameStarted: false,
                startTime: null,
                currentLevelStartTime: null,
                totalTime: 0,
                isPlaying: false
            };

            clearInterval(gameTimer);
            clearInterval(villainTimer);
            updateDisplay();
            document.getElementById('game-over').style.display = 'none';
            document.getElementById('main-menu').style.display = 'flex';
            document.getElementById('vs-panel').classList.remove('visible');
            document.querySelector('.game-area').classList.add('full-width');
            document.getElementById('maze').innerHTML = '';

            // Reset background to default
            document.body.classList.remove('level-1', 'level-2', 'level-3', 'level-4', 'level-5');
        }

        function startTimer() {
            gameTimer = setInterval(() => {
                if (gameState.isPlaying) {
                    updateDisplay();
                }
            }, 100);
        }

        function updateDisplay() {
            const currentTime = gameState.isPlaying ?
                (Date.now() - gameState.currentLevelStartTime) / 1000 : 0;
            const totalTime = gameState.totalTime + currentTime;

            document.getElementById('current-level').textContent = gameState.level;
            document.getElementById('total-time').textContent = totalTime.toFixed(1) + 's';
        }

        async function loadLevel(level) {
            try {
                const response = await fetch(`/maze/${level}`);
                const data = await response.json();
                gameState.maze = data.maze;
                gameState.level = level;
                gameState.playerX = 2;
                gameState.playerY = 2;
                gameState.currentLevelStartTime = Date.now();

                // Update background for difficulty progression
                updateLevelBackground(level);
                updateVSPanel(level);
                spawnVillains();
                renderMaze();
                updateDisplay();
                startVillainMovement();
            } catch (error) {
                console.error('Error loading level:', error);
            }
        }

        function updateLevelBackground(level) {
            // Remove all level classes
            document.body.classList.remove('level-1', 'level-2', 'level-3', 'level-4', 'level-5');
            // Add current level class
            document.body.classList.add(`level-${level}`);
        }


        function spawnVillains() {
            gameState.villains = [];
            // Balanced villain count - Level 1=6, Level 2=12, Level 3=18, Level 4=20, Level 5=27
            const villainCounts = [0, 6, 12, 18, 20, 27];
            const numVillains = villainCounts[gameState.level] || 3;
            const size = gameState.maze.length;

            for (let i = 0; i < numVillains; i++) {
                let x, y;
                let attempts = 0;

                do {
                    x = Math.floor(Math.random() * size);
                    y = Math.floor(Math.random() * size);
                    attempts++;
                } while (attempts < 200 && (
                    gameState.maze[y][x] === 1 ||
                    (x === gameState.playerX && y === gameState.playerY) ||
                    (gameState.maze[y][x] === 3) ||
                    (x >= 1 && x <= 3 && y >= 1 && y <= 3) ||
                    (x >= size-4 && x <= size-2 && y >= size-4 && y <= size-2)
                ));

                if (attempts < 200) {
                    // All villains in a level use the same image (current level villain)
                    const villainId = gameState.level;
                    gameState.villains.push({
                        x: x,
                        y: y,
                        id: villainId,
                        directionX: Math.random() > 0.5 ? 1 : -1,
                        directionY: Math.random() > 0.5 ? 1 : -1
                    });
                }
            }
        }

        function updateVSPanel(level) {
            const villain = villainData[level];
            if (villain) {
                document.getElementById('villain-image').src = `/images/villains/${villain.image}`;
                document.getElementById('villain-name-short').textContent = villain.name.split(' ')[0];
                document.getElementById('villain-title').textContent = villain.title;
                document.getElementById('villain-tagline').textContent = villain.tagline;
            }
        }

        function startVillainMovement() {
            clearInterval(villainTimer);
            // Much faster and more aggressive villains
            const baseSpeeds = [0, 400, 350, 300, 250, 200];
            const moveSpeed = baseSpeeds[gameState.level] || 200;

            villainTimer = setInterval(() => {
                if (gameState.isPlaying && gameState.villains.length > 0) {
                    moveVillains();
                    checkVillainCollisions();
                    updateSpritePositions();
                }
            }, moveSpeed);
        }

        function moveVillains() {
            const size = gameState.maze.length;

            gameState.villains.forEach(villain => {
                let moved = false;

                // Try current direction first
                let newX = villain.x + villain.directionX;
                let newY = villain.y + villain.directionY;

                if (newX >= 0 && newX < size && newY >= 0 && newY < size &&
                    gameState.maze[newY][newX] !== 1) {
                    villain.x = newX;
                    villain.y = newY;
                    moved = true;
                } else {
                    // If blocked, try random directions
                    const directions = [
                        {x: 1, y: 0}, {x: -1, y: 0},
                        {x: 0, y: 1}, {x: 0, y: -1}
                    ];

                    // Shuffle directions for more randomness
                    for (let i = directions.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [directions[i], directions[j]] = [directions[j], directions[i]];
                    }

                    // Try each direction
                    for (const dir of directions) {
                        newX = villain.x + dir.x;
                        newY = villain.y + dir.y;

                        if (newX >= 0 && newX < size && newY >= 0 && newY < size &&
                            gameState.maze[newY][newX] !== 1) {
                            villain.x = newX;
                            villain.y = newY;
                            villain.directionX = dir.x;
                            villain.directionY = dir.y;
                            moved = true;
                            break;
                        }
                    }

                    // If still can't move, occasionally change direction randomly
                    if (!moved && Math.random() < 0.3) {
                        const dir = directions[Math.floor(Math.random() * directions.length)];
                        villain.directionX = dir.x;
                        villain.directionY = dir.y;
                    }
                }
            });
        }

        function checkVillainCollisions() {
            for (let villain of gameState.villains) {
                if (villain.x === gameState.playerX && villain.y === gameState.playerY) {
                    restartLevel();
                    return true;
                }
            }
            return false;
        }

        function restartLevel() {
            console.log('Villain collision! Restarting level...');
            gameState.playerX = 2;
            gameState.playerY = 2;
            spawnVillains();
            // Force complete re-render of the level
            renderMaze();
        }

        function renderMaze() {
            const mazeElement = document.getElementById('maze');
            const size = gameState.maze.length;

            // Apply level theme
            mazeElement.className = `maze level-${gameState.level}`;
            mazeElement.style.gridTemplateColumns = `repeat(${size}, 32px)`;

            // Always recreate maze layout for new levels
            createMazeCells(mazeElement, size);
            updateSpritePositions();
        }

        function createMazeCells(mazeElement, size) {
            mazeElement.innerHTML = '';

            for (let y = 0; y < size; y++) {
                for (let x = 0; x < size; x++) {
                    const cell = document.createElement('div');
                    cell.className = 'cell';
                    cell.dataset.x = x;
                    cell.dataset.y = y;

                    if (gameState.maze[y][x] === 1) {
                        cell.classList.add('wall');
                    } else {
                        cell.classList.add('path');

                        if (gameState.maze[y][x] === 3) {
                            const lamboSprite = document.createElement('div');
                            lamboSprite.className = 'sprite lambo-sprite';
                            lamboSprite.id = 'lambo-sprite';
                            checkImageLoad(lamboSprite, '/images/lambo.jpg');
                            cell.appendChild(lamboSprite);
                        }
                    }

                    mazeElement.appendChild(cell);
                }
            }

            // Create Taylor sprite
            const cellSize = getCellSize();
            const taylorSprite = document.createElement('div');
            taylorSprite.className = 'sprite taylor-sprite';
            taylorSprite.id = 'taylor-sprite';
            taylorSprite.style.position = 'absolute';
            taylorSprite.style.width = cellSize.width + 'px';
            taylorSprite.style.height = cellSize.height + 'px';
            taylorSprite.style.zIndex = '5';
            taylorSprite.style.transition = 'transform 0.15s ease-out';
            checkImageLoad(taylorSprite, '/images/taylor.jpg');
            mazeElement.appendChild(taylorSprite);

            // Create villain sprites
            gameState.villains.forEach((villain, index) => {
                const villainSprite = document.createElement('div');
                villainSprite.className = 'sprite villain-sprite';
                villainSprite.id = `villain-${index}`;
                villainSprite.style.position = 'absolute';
                villainSprite.style.width = cellSize.width + 'px';
                villainSprite.style.height = cellSize.height + 'px';
                villainSprite.style.zIndex = '4';
                villainSprite.style.transition = 'transform 0.3s ease-out';

                const villainImageUrl = `/images/villains/${villainData[villain.id].image}`;
                villainSprite.style.backgroundImage = `url('${villainImageUrl}')`;

                const img = new Image();
                img.onload = function() {
                    villainSprite.classList.add('has-image');
                };
                img.onerror = function() {
                    console.log(`Villain image not found: ${villainImageUrl}, using emoji fallback`);
                };
                img.src = villainImageUrl;

                mazeElement.appendChild(villainSprite);
            });
        }

        function getCellSize() {
            const testCell = document.querySelector('.cell');
            if (testCell) {
                const rect = testCell.getBoundingClientRect();
                return { width: rect.width, height: rect.height };
            }
            return { width: 32, height: 32 }; // fallback
        }

        function updateSpritePositions() {
            const cellSize = getCellSize();
            const gap = 2;
            const padding = 20;
            const stepSize = cellSize.width + gap;

            // Update Taylor position
            const taylorSprite = document.getElementById('taylor-sprite');
            if (taylorSprite) {
                const offsetX = padding + (gameState.playerX * stepSize);
                const offsetY = padding + (gameState.playerY * stepSize);
                taylorSprite.style.transform = `translate(${offsetX}px, ${offsetY}px)`;
                taylorSprite.style.width = cellSize.width + 'px';
                taylorSprite.style.height = cellSize.height + 'px';
            }

            // Update villain positions
            gameState.villains.forEach((villain, index) => {
                const villainSprite = document.getElementById(`villain-${index}`);
                if (villainSprite) {
                    const offsetX = padding + (villain.x * stepSize);
                    const offsetY = padding + (villain.y * stepSize);
                    villainSprite.style.transform = `translate(${offsetX}px, ${offsetY}px)`;
                    villainSprite.style.width = cellSize.width + 'px';
                    villainSprite.style.height = cellSize.height + 'px';
                }
            });
        }

        function checkImageLoad(element, imageUrl) {
            const img = new Image();
            img.onload = function() {
                // Image loaded successfully, don't show fallback
            };
            img.onerror = function() {
                // Image failed to load, show fallback emoji
                element.classList.add('fallback');
            };
            img.src = imageUrl;
        }

        function movePlayer(dx, dy) {
            if (!gameState.isPlaying) return;

            const newX = gameState.playerX + dx;
            const newY = gameState.playerY + dy;

            // Strict boundary and wall collision check
            if (newX < 0 || newX >= gameState.maze[0].length ||
                newY < 0 || newY >= gameState.maze.length ||
                gameState.maze[newY][newX] === 1) {
                return; // Don't move if invalid position
            }

            // Only update position if movement is valid
            gameState.playerX = newX;
            gameState.playerY = newY;

            // Check for villain collision immediately after movement
            if (checkVillainCollisions()) {
                return; // Level was restarted
            }

            // Check for goal
            if (gameState.maze[newY][newX] === 3) {
                levelComplete();
                return;
            }

            // Update visual position
            updateSpritePositions();
        }

        function levelComplete() {
            const levelTime = (Date.now() - gameState.currentLevelStartTime) / 1000;
            gameState.totalTime += levelTime;

            if (gameState.level >= 5) {
                gameComplete();
            } else {
                loadLevel(gameState.level + 1);
            }
        }

        function gameComplete() {
            gameState.isPlaying = false;
            clearInterval(gameTimer);
            clearInterval(villainTimer);

            const finalTime = gameState.totalTime.toFixed(1);
            document.getElementById('final-time').textContent = finalTime + 's';
            document.getElementById('game-over').style.display = 'block';

            submitScore();
        }

        async function submitScore() {
            if (playerName) {
                try {
                    const response = await fetch('/score', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            player_name: playerName,
                            github_username: playerGitHub,
                            total_time: gameState.totalTime,
                            levels_completed: gameState.level
                        })
                    });

                    if (response.ok) {
                        alert('Score submitted successfully! Check the leaderboard to see your ranking!');
                    } else {
                        alert('Error submitting score. Please try again.');
                    }
                } catch (error) {
                    console.error('Error submitting score:', error);
                    alert('Error submitting score. Please try again.');
                }
            }
        }

        document.addEventListener('keydown', (e) => {
            // Handle leaderboard controls
            if (document.getElementById('leaderboard-overlay').style.display === 'flex') {
                if (e.key === 'Escape' || e.key === 'Backspace') {
                    e.preventDefault();
                    hideLeaderboard();
                    return;
                }
                return; // Ignore other keys when leaderboard is open
            }

            // Handle menu controls
            if (!gameState.gameStarted) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    startGame();
                    return;
                }
                if (e.key === 'Escape' || e.key === 'Tab') {
                    e.preventDefault();
                    showLeaderboard();
                    return;
                }
            }

            // Handle game controls
            if (gameState.gameStarted && gameState.isPlaying) {
                switch(e.key.toLowerCase()) {
                    case 'w':
                    case 'arrowup':
                        e.preventDefault();
                        movePlayer(0, -1);
                        break;
                    case 's':
                    case 'arrowdown':
                        e.preventDefault();
                        movePlayer(0, 1);
                        break;
                    case 'a':
                    case 'arrowleft':
                        e.preventDefault();
                        movePlayer(-1, 0);
                        break;
                    case 'd':
                    case 'arrowright':
                        e.preventDefault();
                        movePlayer(1, 0);
                        break;
                }
            }
        });

        function showLeaderboard() {
            document.getElementById('main-menu').style.display = 'none';
            document.getElementById('leaderboard-overlay').style.display = 'flex';
            loadLeaderboard();
        }

        function hideLeaderboard() {
            document.getElementById('leaderboard-overlay').style.display = 'none';
            document.getElementById('main-menu').style.display = 'flex';
        }

        async function loadLeaderboard() {
            try {
                const response = await fetch('/leaderboard');
                const scores = await response.json();

                const container = document.getElementById('leaderboard-content');
                container.innerHTML = '';

                if (scores.length === 0) {
                    container.innerHTML = '<div class="leaderboard-entry"><span class="leaderboard-name">No scores yet! Be the first to complete the game!</span></div>';
                    return;
                }

                const medals = ['🥇', '🥈', '🥉'];
                const podiumClasses = ['first', 'second', 'third'];

                scores.forEach((score, index) => {
                    const entry = document.createElement('div');
                    entry.className = 'leaderboard-entry';

                    if (index < 3) {
                        entry.classList.add('podium', podiumClasses[index]);
                    }

                    const rank = index < 3 ? medals[index] : `${index + 1}.`;
                    const githubLink = score.github_username ?
                        `<div class="leaderboard-github"><a href="https://github.com/${score.github_username}" target="_blank">@${score.github_username}</a></div>` : '';

                    entry.innerHTML = `
                        <div class="leaderboard-rank">${rank}</div>
                        <div class="leaderboard-name">
                            <div class="leaderboard-player-name">${score.player_name}</div>
                            ${githubLink}
                        </div>
                        <div class="leaderboard-time">${score.total_time}s</div>
                    `;
                    container.appendChild(entry);
                });
            } catch (error) {
                console.error('Error loading leaderboard:', error);
                const container = document.getElementById('leaderboard-content');
                container.innerHTML = '<div class="leaderboard-entry"><span class="leaderboard-name">Error loading leaderboard. Please try again.</span></div>';
            }
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && document.getElementById('name-prompt').style.display === 'flex') {
                const nameInput = document.getElementById('player-name-input');
                if (nameInput && document.activeElement === nameInput) {
                    setPlayerName();
                }
            }
        });

        function loadGameLogo() {
            const logoImg = document.getElementById('game-logo');
            const titleText = document.getElementById('game-title');

            const img = new Image();
            img.onload = function() {
                // Logo exists, show it and hide text
                logoImg.style.display = 'block';
                titleText.style.display = 'none';
            };
            img.onerror = function() {
                // Logo doesn't exist, keep text title
                logoImg.style.display = 'none';
                titleText.style.display = 'block';
            };
            img.src = '/images/logo.png';
        }

        async function initializeGame() {
            loadGameLogo();
            updateVSPanel(1);
            checkPlayerName();
            updateDisplay();
            initializeAudio();

            // Add event listeners to resume audio on any user interaction
            document.addEventListener('click', resumeAudioContext, { once: true });
            document.addEventListener('keydown', resumeAudioContext, { once: true });
        }

        initializeGame();
    </script>
</body>
</html>

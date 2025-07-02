import React from 'react';
import ReactDOM from 'react-dom/client';
import DiceGame from './games/Dice/DiceGame.tsx';
import BombMineGame from './games/BombMine/BombMineGame.tsx';
import GroupChat from './components/GroupChat.tsx';
import UserProgressPanel from './components/UserProgressPanel.tsx';
import UserProgressSummary from './components/UserProgressSummary.tsx';

// Renderizar RoletaGame se o elemento existir
const roletaRoot = document.getElementById('roleta-root');
if (roletaRoot) {
    const root = ReactDOM.createRoot(roletaRoot);
    root.render(<RoletaGame />);
}

// Renderizar DiceGame se o elemento existir
const diceRoot = document.getElementById('dice-root');
if (diceRoot) {
    const root = ReactDOM.createRoot(diceRoot);
    root.render(<DiceGame />);
}

// Renderizar BombMineGame se o elemento existir
const bombMineRoot = document.getElementById('bombmine-root');
if (bombMineRoot) {
    const root = ReactDOM.createRoot(bombMineRoot);
    root.render(<BombMineGame />);
}

// Renderizar GroupChat se o elemento existir
const groupChatRoot = document.getElementById('group-chat-root');
if (groupChatRoot) {
    const groupId = groupChatRoot.getAttribute('data-group-id');
    const userId = groupChatRoot.getAttribute('data-user-id');
    const username = groupChatRoot.getAttribute('data-username');
    const root = ReactDOM.createRoot(groupChatRoot);
    root.render(
        <GroupChat
            groupId={groupId || '1'}
            userId={userId || ''}
            username={username || ''}
        />
    );
}

if (window.userData && window.userData.id) {
  if (document.getElementById('user-progress-panel')) {
    ReactDOM.createRoot(document.getElementById('user-progress-panel')).render(
      <UserProgressPanel userId={window.userData.id} />
    );
  }
  if (document.getElementById('user-progress-profile')) {
    ReactDOM.createRoot(document.getElementById('user-progress-profile')).render(
      <UserProgressPanel userId={window.userData.id} />
    );
  }
  if (document.getElementById('user-progress-header')) {
    ReactDOM.createRoot(document.getElementById('user-progress-header')).render(
      <UserProgressSummary userId={window.userData.id} />
    );
  }
}

require('./bootstrap');

import React, { useEffect, useState } from 'react';
import { db } from './firebaseConfig';
import { ref, push, onValue, update, get } from 'firebase/database';

// Função para distribuir pontos aos vencedores
async function distributePollRewards(groupId, pollId = 'active') {
  const pollRef = ref(db, `polls/${groupId}/${pollId}`);
  const snap = await get(pollRef);
  if (!snap.exists()) return;
  const poll = snap.val();
  if (typeof poll.result !== 'number' || !poll.bets) return;

  const winnerIdx = poll.result;
  const bets = poll.bets;

  let totalWinners = 0;
  let totalLosers = 0;
  Object.values(bets).forEach((bet) => {
    if (bet.option === winnerIdx) totalWinners += bet.amount;
    else totalLosers += bet.amount;
  });

  for (const [uid, bet] of Object.entries(bets)) {
    if (bet.option === winnerIdx) {
      const share = bet.amount / totalWinners;
      const reward = bet.amount + Math.floor(share * totalLosers);
      const userRef = ref(db, `users/${uid}`);
      const userSnap = await get(userRef);
      const user = userSnap.val() || {};
      const current = user.poll_points || 0;
      await update(userRef, { poll_points: current + reward });
    }
  }
}

interface Poll {
  id: string;
  question: string;
  options: string[];
  status: 'open' | 'closed';
  created_by: string;
  created_at: number;
  ends_at: number;
  bets?: { [userId: string]: { option: number; amount: number } };
  result?: number;
}

export default function GroupPoll({ groupId, userId, username, pollPoints }: { groupId: string, userId: string, username: string, pollPoints: number }) {
  const [poll, setPoll] = useState<Poll | null>(null);
  const [betAmount, setBetAmount] = useState(0);
  const [selectedOption, setSelectedOption] = useState<number | null>(null);
  const [showCreate, setShowCreate] = useState(false);
  const [question, setQuestion] = useState('');
  const [options, setOptions] = useState(['', '']);
  const [duration, setDuration] = useState(2);

  useEffect(() => {
    const pollRef = ref(db, `polls/${groupId}/active`);
    return onValue(pollRef, (snap) => {
      setPoll(snap.exists() ? { id: snap.key!, ...snap.val() } as Poll : null);
    });
  }, [groupId]);

  const createPoll = async () => {
    if (!question.trim() || options.some(o => !o.trim())) return;
    const pollData = {
      question,
      options,
      status: 'open',
      created_by: userId,
      created_at: Date.now(),
      ends_at: Date.now() + duration * 60 * 1000,
    };
    const pollRef = ref(db, `polls/${groupId}/active`);
    await update(pollRef, pollData);
    setShowCreate(false);
    setQuestion('');
    setOptions(['', '']);
    setDuration(2);
  };

  const placeBet = async () => {
    if (selectedOption === null || betAmount <= 0 || betAmount > pollPoints) return;
    const betRef = ref(db, `polls/${groupId}/active/bets/${userId}`);
    await update(betRef, { option: selectedOption, amount: betAmount });
    const userRef = ref(db, `users/${userId}`);
    await update(userRef, { poll_points: pollPoints - betAmount });
  };

  // Fechar enquete e distribuir pontos
  const closePoll = async (resultIdx: number) => {
    const pollRef = ref(db, `polls/${groupId}/active`);
    await update(pollRef, { status: 'closed', result: resultIdx });
    await distributePollRewards(groupId, 'active');
  };

  // ... restante do componente permanece igual ...
} 
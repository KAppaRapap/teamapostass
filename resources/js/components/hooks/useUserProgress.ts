import { useEffect, useState } from 'react';
import { db } from '../firebaseConfig'; // Importa do arquivo JS
import { ref, onValue, update } from 'firebase/database';

export interface UserProgress {
  poll_points: number;
  level: number;
  xp: number;
  xp_next_level: number;
  total_earnings: number;
  monthly_goal: number;
  games_played: number;
  weekly_goal: number;
}

export function useUserProgress(userId: string) {
  const [progress, setProgress] = useState<UserProgress | null>(null);

  useEffect(() => {
    if (!userId) return;
    const userRef = ref(db, `users/${userId}`);
    const unsubscribe = onValue(userRef, (snapshot) => {
      setProgress(snapshot.val());
    });
    return () => unsubscribe();
  }, [userId]);

  // Função para atualizar progresso
  const updateProgress = (data: Partial<UserProgress>) => {
    const userRef = ref(db, `users/${userId}`);
    update(userRef, data);
  };

  return { progress, updateProgress };
} 
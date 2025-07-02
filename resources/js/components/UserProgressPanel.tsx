import React from 'react';
import { useUserProgress } from './hooks/useUserProgress.ts';

interface Props {
  userId: string;
}

export default function UserProgressPanel({ userId }: Props) {
  const { progress } = useUserProgress(userId);

  if (!progress) return (
    <div className="bg-[#18191c] rounded-2xl shadow-xl p-6 max-w-md mx-auto text-white">
      <div className="animate-pulse space-y-4">
        <div className="h-4 bg-gray-700 rounded w-3/4"></div>
        <div className="h-3 bg-gray-700 rounded"></div>
        <div className="h-4 bg-gray-700 rounded w-1/2"></div>
        <div className="h-3 bg-gray-700 rounded"></div>
        <div className="h-4 bg-gray-700 rounded w-2/3"></div>
        <div className="h-3 bg-gray-700 rounded"></div>
      </div>
    </div>
  );

  const xpPercent = Math.min(100, (progress.xp / progress.xp_next_level) * 100);
  const monthlyPercent = Math.min(100, (progress.total_earnings / progress.monthly_goal) * 100);
  const weeklyPercent = Math.min(100, (progress.games_played / progress.weekly_goal) * 100);

  return (
    <div className="bg-[#18191c] rounded-2xl shadow-xl p-6 max-w-md mx-auto text-white space-y-6">
      <div>
        <div className="flex justify-between items-center mb-1">
          <span className="font-bold text-lg">Nível Atual</span>
          <span className="font-bold text-green-400">{progress.level}</span>
        </div>
        <div className="w-full bg-[#23262b] rounded h-3">
          <div className="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded" style={{ width: `${xpPercent}%` }} />
        </div>
        <div className="text-xs text-gray-400 mt-1">{xpPercent.toFixed(0)}% para o próximo nível</div>
      </div>
      <div>
        <div className="flex justify-between items-center mb-1">
          <span className="font-bold">Ganhos Totais</span>
          <span className="font-bold text-yellow-400">€{progress.total_earnings.toLocaleString()}</span>
        </div>
        <div className="w-full bg-[#23262b] rounded h-3">
          <div className="bg-gradient-to-r from-yellow-400 to-yellow-600 h-3 rounded" style={{ width: `${monthlyPercent}%` }} />
        </div>
        <div className="text-xs text-gray-400 mt-1">{monthlyPercent.toFixed(0)}% da meta mensal</div>
      </div>
      <div>
        <div className="flex justify-between items-center mb-1">
          <span className="font-bold">Jogos Jogados</span>
          <span className="font-bold text-blue-400">{progress.games_played}</span>
        </div>
        <div className="w-full bg-[#23262b] rounded h-3">
          <div className="bg-gradient-to-r from-blue-400 to-blue-600 h-3 rounded" style={{ width: `${weeklyPercent}%` }} />
        </div>
        <div className="text-xs text-gray-400 mt-1">{weeklyPercent.toFixed(0)}% da meta semanal</div>
      </div>
      <div>
        <span className="font-bold">Pontos de Enquete:</span>
        <span className="ml-2 text-pink-400">{progress.poll_points}</span>
      </div>
    </div>
  );
} 
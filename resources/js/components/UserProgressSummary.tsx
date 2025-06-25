import React from 'react';
import { useUserProgress } from './hooks/useUserProgress.ts';

export default function UserProgressSummary({ userId }: { userId: string }) {
  const { progress } = useUserProgress(userId);

  if (!progress) return null;

  return (
    <div className="flex items-center gap-3 text-white">
      <span className="font-bold text-green-400">Nível {progress.level}</span>
      <span className="bg-pink-500 text-white rounded px-2 py-1 text-xs font-bold">
        {progress.poll_points} pts
      </span>
    </div>
  );
} 
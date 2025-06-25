import React, { useState, useEffect, useRef } from 'react';
import { db } from './firebaseConfig';
import {
  ref,
  push,
  onChildAdded,
  query,
  orderByChild,
  get
} from 'firebase/database';
import { getStorage, ref as storageRef, uploadBytes, getDownloadURL } from 'firebase/storage';
// @ts-ignore
import EmojiPicker from 'emoji-picker-react';

interface Message {
  id: string;
  userId: string;
  username: string;
  text: string;
  timestamp: string;
}

interface GroupChatProps {
  groupId: string;
  userId: string;
  username: string;
}

// Função utilitária para formatar tamanho
function formatBytes(bytes: number) {
  if (bytes === 0) return '0 B';
  const k = 1024;
  const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

export default function GroupChat({ groupId, userId, username }: GroupChatProps) {
  const [messages, setMessages] = useState<Message[]>([]);
  const [newMessage, setNewMessage] = useState('');
  const [isConnected, setIsConnected] = useState(true); // Assume conectado
  const messagesEndRef = useRef<HTMLDivElement>(null);
  const [showEmojiPicker, setShowEmojiPicker] = useState(false);
  const [attachedFile, setAttachedFile] = useState<File | null>(null);
  const [showBetModal, setShowBetModal] = useState(false);
  const [betText, setBetText] = useState('');

  // Carregar histórico e ouvir novas mensagens em tempo real
  useEffect(() => {
    if (!groupId) return;
    setMessages([]); // Limpa ao trocar de grupo
    const messagesRef = ref(db, `group_chats/${groupId}/messages`);
    const q = query(messagesRef, orderByChild('timestamp'));

    // Carrega histórico inicial
    get(q).then(snapshot => {
      const loaded: Message[] = [];
      snapshot.forEach(child => {
        const val = child.val();
        loaded.push({
          id: child.key!,
          userId: val.userId,
          username: val.username,
          text: val.text,
          timestamp: val.timestamp
        });
      });
      setMessages(loaded);
    });

    // Listener para novas mensagens
    const unsubscribe = onChildAdded(messagesRef, (data) => {
      const val = data.val();
      setMessages(prev => {
        // Evita duplicatas
        if (prev.find(m => m.id === data.key)) return prev;
        return [
          ...prev,
          {
            id: data.key!,
            userId: val.userId,
            username: val.username,
            text: val.text,
            timestamp: val.timestamp
          }
        ];
      });
    });
    return () => {
      unsubscribe();
    };
  }, [groupId]);

  // Auto-scroll para a última mensagem
  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [messages]);

  const sendMessage = async () => {
    if (!newMessage.trim() || !isConnected) return;
    const now = new Date();
    const msg = {
      userId,
      username,
      text: newMessage.trim(),
      timestamp: now.toISOString()
    };
    const messagesRef = ref(db, `group_chats/${groupId}/messages`);
    await push(messagesRef, msg);
    setNewMessage('');
  };

  const handleKeyPress = (e: React.KeyboardEvent) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  };

  const formatTime = (iso: string) => {
    const date = new Date(iso);
    return date.toLocaleTimeString('pt-BR', {
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  const isOwnMessage = (message: Message) => {
    return message.userId === userId;
  };

  const handleEmojiClick = (emojiData: any) => {
    setNewMessage(newMessage + emojiData.emoji);
    setShowEmojiPicker(false);
  };

  const handleAttachFile = async (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      const file = e.target.files[0];
      setAttachedFile(file);
      // Upload para Firebase Storage
      const storage = getStorage();
      const filePath = `group_chats/${groupId}/uploads/${Date.now()}_${file.name}`;
      const fileRef = storageRef(storage, filePath);
      await uploadBytes(fileRef, file);
      const url = await getDownloadURL(fileRef);
      // Se imagem, insere tag <img>, senão, link HTML
      const isImage = file.type.startsWith('image/');
      setNewMessage(newMessage + (isImage ? ` <img src='${url}' alt='imagem' style='max-width:150px;display:inline;'/> ` : ` <a href='${url}' download target='_blank'>${file.name}</a> `));
    }
  };

  const handleSendBet = () => {
    setNewMessage(newMessage + ` [Aposta: ${betText}]`);
    setShowBetModal(false);
    setBetText('');
  };

  return (
    <div className="max-w-4xl mx-auto p-4">
      <div className="bg-[#18191c] rounded-2xl shadow-2xl border border-[#23262b]">
        {/* Header */}
        <div className="bg-gradient-to-r from-[#1e293b] to-[#23262b] text-white p-4 rounded-t-2xl border-b border-[#23262b] flex items-center justify-between">
          <div>
            <h1 className="text-lg md:text-xl font-bold flex items-center gap-2">
              <span className="inline-block text-green-400">💬</span> Chat do Grupo <span className="text-blue-400">#{groupId}</span>
            </h1>
            <p className="text-xs text-gray-400 mt-1">{isConnected ? '🟢 Conectado' : '🔴 Desconectado'}</p>
          </div>
          <div className="text-right">
            <span className="text-xs text-gray-400">{messages.length} mensagens</span>
          </div>
        </div>

        {/* Mensagens */}
        <div className="h-96 overflow-y-auto p-4 bg-[#1a1b1e] rounded-b-lg">
          <div className="space-y-4">
            {messages.map((message) => {
              // Detecta se é um anexo (link HTML gerado pelo upload)
              const fileRegex = /<a href='([^']+)' download target='_blank'>([^<]+)<\/a>/;
              const match = message.text.match(fileRegex);
              if (match) {
                const url = match[1];
                const name = match[2];
                // Não temos o tamanho salvo, mas podemos tentar buscar via HEAD futuramente
                // Por enquanto, só mostra o nome e ícone
                const ext = name.split('.').pop()?.toLowerCase() || '';
                let icon = '📄';
                if (['jpg','jpeg','png','gif','bmp','webp'].includes(ext)) icon = '🖼️';
                if (['pdf'].includes(ext)) icon = '📕';
                if (['zip','rar','7z'].includes(ext)) icon = '🗜️';
                if (['doc','docx'].includes(ext)) icon = '📄';
                if (['xls','xlsx'].includes(ext)) icon = '📊';
                if (['ppt','pptx'].includes(ext)) icon = '📈';
                return (
                  <div key={message.id} className={`flex ${isOwnMessage(message) ? 'justify-end' : 'justify-start'}`}>
                    <div className={`rounded-xl px-4 py-2 max-w-[70%] break-words shadow-md ${isOwnMessage(message) ? 'bg-gradient-to-r from-green-500/80 to-green-400/80 text-white' : 'bg-[#23262b] text-gray-100 border border-[#23262b]'}`}>
                      <span className="font-semibold text-sm">{message.username}</span> <span className="text-xs text-gray-400">{formatTime(message.timestamp)}</span>
                      <div className="mt-1">
                        <span>{icon}</span> <a href={url} download target="_blank" className="underline text-blue-300">{name}</a>
                      </div>
                    </div>
                  </div>
                );
              }
              // Detecta se é uma imagem inline
              const imgRegex = /<img src='([^']+)' alt='imagem' style='max-width:150px;display:inline;'\/>/;
              const imgMatch = message.text.match(imgRegex);
              if (imgMatch) {
                const url = imgMatch[1];
                return (
                  <div key={message.id} className={`flex ${isOwnMessage(message) ? 'justify-end' : 'justify-start'}`}>
                    <div className={`rounded-xl px-4 py-2 max-w-[70%] break-words shadow-md ${isOwnMessage(message) ? 'bg-gradient-to-r from-green-500/80 to-green-400/80 text-white' : 'bg-[#23262b] text-gray-100 border border-[#23262b]'}`}>
                      <span className="font-semibold text-sm">{message.username}</span> <span className="text-xs text-gray-400">{formatTime(message.timestamp)}</span>
                      <div className="mt-1">
                        <img src={url} alt="imagem" style={{ maxWidth: '150px', display: 'inline', borderRadius: '8px' }} />
                      </div>
                    </div>
                  </div>
                );
              }
              // Mensagem normal
              return (
                <div key={message.id} className={`flex ${isOwnMessage(message) ? 'justify-end' : 'justify-start'}`}>
                  <div className={`rounded-xl px-4 py-2 max-w-[70%] break-words shadow-md ${isOwnMessage(message) ? 'bg-gradient-to-r from-green-500/80 to-green-400/80 text-white' : 'bg-[#23262b] text-gray-100 border border-[#23262b]'}`}>
                    <span className="font-semibold text-sm">{message.username}</span> <span className="text-xs text-gray-400">{formatTime(message.timestamp)}</span>
                    <div className="mt-1 whitespace-pre-line">{message.text}</div>
                  </div>
                </div>
              );
            })}
            <div ref={messagesEndRef} />
          </div>
        </div>

        {/* Input */}
        <div className="p-4 border-t border-[#23262b] bg-[#18191c] flex items-center gap-2 rounded-b-2xl">
          <button
            className="text-2xl hover:scale-110 transition-transform"
            onClick={() => setShowEmojiPicker(!showEmojiPicker)}
            title="Emoji"
            style={{ color: '#22d3ee' }}
          >
            😊
          </button>
          {showEmojiPicker && (
            <div className="absolute z-10">
              <EmojiPicker onEmojiClick={handleEmojiClick} />
            </div>
          )}
          <input
            type="text"
            className="flex-1 border border-[#23262b] bg-[#23262b] text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 placeholder-gray-400"
            placeholder="Digite sua mensagem..."
            value={newMessage}
            onChange={e => setNewMessage(e.target.value)}
            onKeyPress={handleKeyPress}
          />
          <button
            className="bg-gradient-to-r from-green-500 to-green-400 text-white px-4 py-2 rounded-lg font-semibold shadow hover:from-green-600 hover:to-green-500 transition-colors disabled:opacity-50"
            onClick={sendMessage}
            disabled={!newMessage.trim() || !isConnected}
          >
            Enviar
          </button>
        </div>
      </div>
    </div>
  );
} 
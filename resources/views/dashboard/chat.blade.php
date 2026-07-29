<!-- 2. Tab: Chat Percakapan -->
<div x-show="tab === 'chat'" class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-sm h-[600px] flex flex-col md:flex-row">
    <!-- Left Pane: Conversations List -->
    <div class="w-full md:w-80 border-r border-slate-200 dark:border-zinc-800 flex flex-col flex-shrink-0">
        <div class="p-4 border-b border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950">
            <h3 class="font-bold text-sm">Percakapan Aktif</h3>
        </div>
        <div class="overflow-y-auto flex-grow divide-y divide-slate-100 dark:divide-zinc-850">
            <template x-for="conv in conversations" :key="conv.id">
                <button @click="selectConversation(conv.id)" :class="activeChatId === conv.id ? 'bg-indigo-50 dark:bg-indigo-950/20' : 'hover:bg-slate-50 dark:hover:bg-zinc-850/50'" class="w-full text-left p-4 transition-colors flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-zinc-800 flex items-center justify-center font-bold text-xs uppercase text-slate-600 dark:text-slate-350">
                        <span x-text="getChatPartnerName(conv).substring(0, 2)"></span>
                    </div>
                    <div class="overflow-hidden flex-grow text-xs">
                        <div class="flex items-center justify-between">
                            <p class="font-bold text-slate-800 dark:text-slate-200 truncate" x-text="getChatPartnerName(conv)"></p>
                            <span x-show="getUserStatusText(conv) === 'Online'" class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0 animate-pulse"></span>
                        </div>
                        <p class="text-slate-400 truncate mt-0.5" x-text="conv.property ? conv.property.title : 'Properti'"></p>
                        <p class="text-[9px] mt-0.5 font-semibold" :class="getUserStatusText(conv) === 'Online' ? 'text-emerald-500' : 'text-slate-400'" x-text="getUserStatusText(conv)"></p>
                    </div>
                </button>
            </template>
            <template x-if="conversations.length === 0">
                <p class="text-center py-12 text-slate-400 italic text-sm">Tidak ada chat aktif.</p>
            </template>
        </div>
    </div>

    <!-- Right Pane: Active Messages Box -->
    <div class="flex-grow flex flex-col h-full bg-slate-50 dark:bg-zinc-950">
        <template x-if="activeChatId">
            <div class="flex flex-col h-full overflow-hidden">
                <!-- Chat Header -->
                <div class="p-4 border-b border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-sm text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                            <span x-text="activeChatPartnerName"></span>
                            <span x-show="getActiveChatStatus() === 'Online'" class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-ping"></span>
                            <span x-show="getActiveChatStatus() === 'Online'" class="w-2 h-2 rounded-full bg-emerald-500 inline-block -ml-3.5"></span>
                        </h4>
                        <p class="text-[10px] text-slate-400 mt-0.5 flex items-center gap-1.5">
                            <span x-text="activeChatPropertyTitle"></span>
                            <span class="text-slate-300 dark:text-slate-700">•</span>
                            <span :class="getActiveChatStatus() === 'Online' ? 'text-emerald-500 font-bold' : 'text-slate-400'" x-text="getActiveChatStatus()"></span>
                        </p>
                    </div>
                </div>

                <!-- Messages Area -->
                <div id="chat-messages-box" class="flex-grow p-4 overflow-y-auto space-y-4">
                    <template x-for="msg in messages" :key="msg.id">
                        <div :class="msg.sender_id === {{ Auth::id() }} ? 'justify-end' : 'justify-start'" class="flex">
                            <div :class="msg.sender_id === {{ Auth::id() }} ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white dark:bg-zinc-900 text-slate-800 dark:text-slate-200 rounded-tl-none border border-slate-200/60 dark:border-zinc-800'" class="max-w-[70%] p-3 px-3.5 rounded-2xl text-xs shadow-sm space-y-1.5">
                                <template x-if="msg.image_url">
                                    <div class="relative rounded-xl overflow-hidden border border-slate-100 dark:border-zinc-800/80 bg-slate-900/10">
                                        <img :src="msg.image_url" alt="Gambar terlampir" class="max-w-full max-h-60 object-contain rounded-xl cursor-pointer hover:scale-[1.01] transition-transform duration-200" @click="showZoomedChatImage(msg.image_url)">
                                    </div>
                                </template>
                                <p x-show="msg.message" x-text="msg.message"></p>
                                <span :class="msg.sender_id === {{ Auth::id() }} ? 'text-indigo-200' : 'text-slate-400'" class="text-[9px] block text-right mt-1.5" x-text="formatDate(msg.created_at)"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Send Input Area -->
                <div x-data="{ chatImageSelected: '' }" class="border-t border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 flex flex-col">
                    <!-- File Upload Preview Chip -->
                    <div x-show="chatImageSelected" class="px-4 py-2 bg-slate-50 dark:bg-zinc-950 border-b border-slate-100 dark:border-zinc-850 flex items-center justify-between text-[11px] text-slate-500">
                        <span class="flex items-center gap-1.5 font-bold text-indigo-600 dark:text-indigo-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            Gambar Terpilih: <span x-text="chatImageSelected" class="truncate max-w-[250px]"></span>
                        </span>
                        <button type="button" @click="chatImageSelected = ''; document.getElementById('chat-image-input').value = ''" class="text-rose-500 font-bold hover:text-rose-600">Batal</button>
                    </div>

                    <form @submit.prevent="sendChatMessage(); chatImageSelected = ''" class="p-4 flex gap-2 items-center">
                        <!-- Hidden File Input -->
                        <input type="file" id="chat-image-input" accept="image/*" class="hidden" 
                            @change="chatImageSelected = $event.target.files[0] ? $event.target.files[0].name : ''">
                        
                        <!-- Attach Image Button -->
                        <button type="button" @click="document.getElementById('chat-image-input').click()" 
                            class="p-2.5 border border-slate-200 dark:border-zinc-800 rounded-xl bg-slate-50 dark:bg-zinc-950 hover:bg-slate-100 dark:hover:bg-zinc-900 text-slate-400 hover:text-indigo-600 transition-colors flex-shrink-0"
                            title="Lampirkan Gambar">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </button>

                        <input type="text" x-model="chatInput" placeholder="Tulis pesan Anda..." :required="!chatImageSelected"
                            class="flex-grow px-4 py-2.5 rounded-xl border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-xs outline-none focus:ring-2 focus:ring-indigo-500/20">
                        <button type="submit" class="py-2.5 px-5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl text-xs active:scale-95 transition-all flex-shrink-0">
                            Kirim
                        </button>
                    </form>
                </div>
            </div>
        </template>
        <template x-if="!activeChatId">
            <div class="h-full flex flex-col items-center justify-center text-slate-400 p-8">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mb-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A9 9 0 0112 3v0a9 9 0 019 9v.75m-.51 5.495a8.995 8.995 0 01-12.44 0L12 12l5.49 5.245z" />
                </svg>
                <p class="text-sm italic">Pilih salah satu percakapan di sebelah kiri untuk melihat pesan.</p>
            </div>
        </template>
    </div>
</div>

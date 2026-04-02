<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus | The Student OS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <script type="module">
        import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';
        mermaid.initialize({ startOnLoad: false, theme: 'default', fontFamily: 'Plus Jakarta Sans' });
        window.mermaid = mermaid;
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-panel { background: rgba(255, 255, 255, 0.8); border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 16px; backdrop-filter: blur(10px); }
        .dark .glass-panel { background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05); }
        .sidebar-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; font-weight: 500; font-size: 0.875rem; transition: all 0.2s; cursor: pointer; }
        .sidebar-item:hover, .sidebar-item.active { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
        .dark .sidebar-item:hover, .dark .sidebar-item.active { color: #818cf8; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }
    </style>
</head>
<body class="h-screen flex overflow-hidden bg-slate-50 text-slate-800 dark:bg-gradient-to-br dark:from-[#09090b] dark:to-[#121218] dark:text-f8fafc transition-colors duration-300">

    <aside class="w-64 border-r border-slate-200 dark:border-white/5 bg-white dark:bg-[#0c0c10] flex flex-col justify-between hidden md:flex transition-colors duration-300">
        <div>
            <div class="h-20 flex items-center px-6 border-b border-slate-200 dark:border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center font-bold text-white shadow-lg shadow-indigo-500/20">N</div>
                    <span class="font-extrabold tracking-tight text-xl text-slate-900 dark:text-white">NEXUS</span>
                </div>
            </div>
            
            <div class="p-4 space-y-2 mt-4">
                <div class="text-[10px] uppercase tracking-widest text-slate-400 dark:text-slate-500 font-bold mb-4 px-4">Workspace</div>
                <div class="sidebar-item active text-slate-600 dark:text-slate-400"><i class="ph ph-chat-teardrop-text text-lg"></i> AI Tutor</div>
                <div class="sidebar-item text-slate-600 dark:text-slate-400"><i class="ph ph-exam text-lg"></i> Mock Exams <span class="ml-auto bg-slate-100 dark:bg-white/10 text-[9px] px-2 py-0.5 rounded-full">Soon</span></div>
                <div class="sidebar-item text-slate-600 dark:text-slate-400"><i class="ph ph-headphones text-lg"></i> Audio Lab <span class="ml-auto bg-slate-100 dark:bg-white/10 text-[9px] px-2 py-0.5 rounded-full">Soon</span></div>
            </div>
        </div>

        <div class="p-4 border-t border-slate-200 dark:border-white/5">
            <div class="flex items-center gap-3 p-2">
                <div class="w-8 h-8 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center border border-slate-200 dark:border-white/10"><i class="ph ph-user"></i></div>
                <div>
                    <div class="text-xs font-bold text-slate-900 dark:text-white">Student User</div>
                    <div class="text-[10px] text-indigo-500 dark:text-indigo-400 font-mono">v3.1 // ONLINE</div>
                </div>
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <header class="h-20 border-b border-slate-200 dark:border-white/5 flex items-center justify-between px-8 bg-white/50 dark:bg-black/20 backdrop-blur-md transition-colors duration-300">
            <div>
                <h1 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">Active Study Session</h1>
                <div id="statusIndicator" class="hidden text-[10px] text-indigo-500 dark:text-indigo-400 font-mono animate-pulse mt-1">PROCESSING_NEURAL_LINK...</div>
            </div>
            
            <div class="flex items-center gap-4">
                <button onclick="toggleTheme()" class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10 transition">
                    <i id="themeIcon" class="ph ph-sun text-lg"></i>
                </button>

                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Persona:</span>
                    <select id="personaSelect" class="bg-white dark:bg-[#18181f] border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white text-xs rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 cursor-pointer">
                        <option value="chill">Supportive Tutor</option>
                        <option value="strict">Strict Lecturer</option>
                    </select>
                </div>
            </div>
        </header>

        <div class="flex-1 flex overflow-hidden p-6 gap-6">
            
            <section class="w-[45%] flex flex-col gap-4">
                <div class="glass-panel p-5 flex-1 flex flex-col gap-4 shadow-sm">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-xs uppercase tracking-widest font-bold text-indigo-600 dark:text-indigo-400"><i class="ph ph-books mr-1"></i> Context Source</h3>
                        <div class="flex gap-2">
                            <label class="cursor-pointer bg-slate-100 dark:bg-[#18181f] hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center gap-2 border border-slate-200 dark:border-white/10">
                                <i class="ph ph-image text-sm"></i> Image
                                <input type="file" id="imageInput" accept="image/*" class="hidden" onchange="attachImage()">
                            </label>
                            <label class="cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/20">
                                <i class="ph ph-file-pdf text-sm"></i> PDF
                                <input type="file" id="pdfInput" accept=".pdf" class="hidden" onchange="extractPDFText()">
                            </label>
                        </div>
                    </div>
                    
                    <textarea id="sourceText" class="flex-1 bg-slate-50 dark:bg-black/40 p-4 rounded-xl focus:outline-none focus:ring-1 focus:ring-indigo-500 text-slate-700 dark:text-slate-300 leading-relaxed resize-none text-sm border border-slate-200 dark:border-white/5 transition-colors" placeholder="Paste your lecture notes here..."></textarea>
                    
                    <div id="imagePreviewContainer" class="hidden bg-indigo-50 dark:bg-indigo-500/10 p-3 rounded-lg border border-indigo-100 dark:border-indigo-500/20 flex justify-between items-center">
                        <span class="text-xs text-indigo-600 dark:text-indigo-300 font-mono flex items-center gap-2"><i class="ph ph-check-circle"></i> Image attached.</span>
                        <button onclick="removeImage()" class="text-red-500 dark:text-red-400 text-xs hover:underline">Remove</button>
                    </div>
                </div>

                <div class="glass-panel p-4 grid grid-cols-2 gap-3 shadow-sm">
                    <button onclick="triggerAction('mindmap')" class="bg-indigo-50 dark:bg-indigo-600/10 hover:bg-indigo-100 dark:hover:bg-indigo-600/30 border border-indigo-200 dark:border-indigo-500/20 text-indigo-600 dark:text-indigo-300 py-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                        <i class="ph ph-tree-structure text-lg"></i> Draw Mind Map
                    </button>
                    <button onclick="alert('Mock Exams coming soon!')" class="bg-purple-50 dark:bg-purple-600/10 hover:bg-purple-100 dark:hover:bg-purple-600/30 border border-purple-200 dark:border-purple-500/20 text-purple-600 dark:text-purple-300 py-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                        <i class="ph ph-exam text-lg"></i> Generate Quiz
                    </button>
                </div>
            </section>

            <section class="w-[55%] flex flex-col glass-panel overflow-hidden shadow-sm">
                <div id="chatBox" class="flex-1 overflow-y-auto p-6 space-y-6 scroll-smooth">
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex-shrink-0 flex items-center justify-center text-white shadow-lg"><i class="ph ph-robot"></i></div>
                        <div class="bg-white dark:bg-[#18181f] p-4 rounded-2xl rounded-tl-sm text-sm leading-relaxed max-w-[85%] text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/5 shadow-sm transition-colors">
                            Welcome to Nexus. Light mode or Dark mode, the brain is ready. Load up your materials.
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-[#09090b] transition-colors">
                    <div class="bg-white dark:bg-[#18181f] flex items-center p-1.5 gap-2 pr-2 border border-slate-200 dark:border-white/10 rounded-2xl shadow-inner focus-within:border-indigo-500/50 transition-colors">
                        <input type="text" id="userInput" class="flex-1 bg-transparent p-3 text-sm focus:outline-none text-slate-800 dark:text-white pl-4" placeholder="Ask Nexus anything about your notes...">
                        <button onclick="triggerAction('chat')" id="sendBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white p-2.5 rounded-xl transition shadow-lg shadow-indigo-600/20">
                            <i class="ph ph-paper-plane-right text-lg"></i>
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script>
        // THEME TOGGLE LOGIC
        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            document.getElementById('themeIcon').className = isDark ? 'ph ph-sun text-lg' : 'ph ph-moon text-lg';
        }

        // KEEP PDF/VISION/CHAT LOGIC EXACTLY THE SAME
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
        let attachedImageBase64 = null;
        let attachedImageMime = null;

        function attachImage() {
            const file = document.getElementById('imageInput').files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const result = e.target.result;
                attachedImageMime = result.split(';')[0].split(':')[1];
                attachedImageBase64 = result.split(',')[1];
                document.getElementById('imagePreviewContainer').classList.remove('hidden');
                document.getElementById('imagePreviewContainer').classList.add('flex');
            };
            reader.readAsDataURL(file);
        }

        function removeImage() {
            attachedImageBase64 = null;
            attachedImageMime = null;
            document.getElementById('imageInput').value = '';
            document.getElementById('imagePreviewContainer').classList.add('hidden');
            document.getElementById('imagePreviewContainer').classList.remove('flex');
        }

        async function extractPDFText() {
            const file = document.getElementById('pdfInput').files[0];
            const sourceArea = document.getElementById('sourceText');
            if (!file) return;
            document.getElementById('statusIndicator').classList.remove('hidden');
            sourceArea.value = "Scanning PDF content... please wait.";

            const reader = new FileReader();
            reader.onload = async function() {
                try {
                    const typedarray = new Uint8Array(this.result);
                    const pdf = await pdfjsLib.getDocument(typedarray).promise;
                    let fullText = '';
                    for (let i = 1; i <= pdf.numPages; i++) {
                        const page = await pdf.getPage(i);
                        const content = await page.getTextContent();
                        const strings = content.items.map(item => item.str);
                        fullText += strings.join(' ') + '\n';
                    }
                    sourceArea.value = fullText;
                } catch (err) {
                    alert("Error reading PDF.");
                } finally {
                    document.getElementById('statusIndicator').classList.add('hidden');
                }
            };
            reader.readAsArrayBuffer(file);
        }

        async function triggerAction(actionType) {
            const notes = document.getElementById('sourceText').value;
            let question = document.getElementById('userInput').value;
            const chatBox = document.getElementById('chatBox');
            const persona = document.getElementById('personaSelect').value;
            
            if(!notes && !attachedImageBase64) return alert("Provide notes or an image first.");
            if(actionType === 'chat' && !question) return alert("Type a question.");
            if(actionType === 'mindmap') question = "Generate a Mind Map";

            chatBox.innerHTML += `
                <div class="flex gap-4 justify-end">
                    <div class="bg-indigo-600 text-white p-4 rounded-2xl rounded-tr-sm text-sm max-w-[85%] shadow-md">
                        ${question}
                        ${attachedImageBase64 ? '<br><span class="text-[10px] text-indigo-200 mt-2 block"><i class="ph ph-image mr-1"></i>Image Attached</span>' : ''}
                    </div>
                </div>`;
            
            document.getElementById('userInput').value = "";
            chatBox.scrollTop = chatBox.scrollHeight;
            document.getElementById('statusIndicator').classList.remove('hidden');

            const formData = new FormData();
            formData.append('notes', notes);
            formData.append('question', actionType === 'chat' ? question : "Create a detailed summary.");
            formData.append('actionType', actionType);
            formData.append('persona', persona);
            if (attachedImageBase64) {
                formData.append('image', attachedImageBase64);
                formData.append('mime', attachedImageMime);
            }

            try {
                const response = await fetch('process.php', { method: 'POST', body: formData });
                const text = await response.text();

                if (text.includes('```mermaid')) {
                    const match = text.match(/```mermaid([\s\S]*?)```/);
                    if (match && window.mermaid) {
                        const mermaidCode = match[1].trim();
                        const uniqueId = 'mermaid-' + Date.now();
                        chatBox.innerHTML += `
                            <div class="flex gap-4">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 flex-shrink-0 flex items-center justify-center text-white shadow-lg"><i class="ph ph-tree-structure"></i></div>
                                <div class="bg-white dark:bg-[#18181f] p-4 text-sm max-w-[95%] text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/5 rounded-2xl rounded-tl-sm w-full overflow-x-auto shadow-sm">
                                    <div id="${uniqueId}" class="flex justify-center bg-slate-50 dark:bg-black/40 p-6 rounded-xl border border-slate-200 dark:border-white/5"></div>
                                </div>
                            </div>`;
                        const { svg } = await window.mermaid.render('graph-' + uniqueId, mermaidCode);
                        document.getElementById(uniqueId).innerHTML = svg;
                    }
                } else {
                    chatBox.innerHTML += `
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex-shrink-0 flex items-center justify-center text-white shadow-lg"><i class="ph ph-robot"></i></div>
                            <div class="bg-white dark:bg-[#18181f] p-4 rounded-2xl rounded-tl-sm text-sm leading-relaxed max-w-[85%] text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/5 shadow-sm">
                                ${text}
                            </div>
                        </div>`;
                }
            } catch (err) {
                chatBox.innerHTML += `<div class="text-red-500 text-xs text-center font-mono my-4">ERROR_CONNECTING_TO_NEXUS_CORE</div>`;
            } finally {
                document.getElementById('statusIndicator').classList.add('hidden');
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }
    </script>
</body>
</html>

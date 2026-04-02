<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus | The Student OS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <script type="module">
        import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';
        mermaid.initialize({ startOnLoad: false, theme: 'dark', fontFamily: 'Plus Jakarta Sans' });
        window.mermaid = mermaid;
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #09090b; color: #f8fafc; }
        .glass-panel { background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 16px; backdrop-filter: blur(10px); }
        .sidebar-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #94a3b8; font-weight: 500; font-size: 0.875rem; transition: all 0.2s; cursor: pointer; }
        .sidebar-item:hover, .sidebar-item.active { background: rgba(99, 102, 241, 0.1); color: #818cf8; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>
<body class="h-screen flex overflow-hidden">

    <aside class="w-64 border-r border-white/5 bg-[#0c0c10] flex flex-col justify-between hidden md:flex">
        <div>
            <div class="h-20 flex items-center px-6 border-b border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center font-bold text-white shadow-lg shadow-indigo-500/20">N</div>
                    <span class="font-extrabold tracking-tight text-xl text-white">NEXUS</span>
                </div>
            </div>
            
            <div class="p-4 space-y-2 mt-4">
                <div class="text-[10px] uppercase tracking-widest text-slate-500 font-bold mb-4 px-4">Workspace</div>
                <div class="sidebar-item active"><i class="ph ph-chat-teardrop-text text-lg"></i> AI Tutor</div>
                <div class="sidebar-item"><i class="ph ph-exam text-lg"></i> Mock Exams <span class="ml-auto bg-white/10 text-[9px] px-2 py-0.5 rounded-full">Soon</span></div>
                <div class="sidebar-item"><i class="ph ph-headphones text-lg"></i> Audio Lab <span class="ml-auto bg-white/10 text-[9px] px-2 py-0.5 rounded-full">Soon</span></div>
                <div class="sidebar-item"><i class="ph ph-folder-open text-lg"></i> File Vault <span class="ml-auto bg-white/10 text-[9px] px-2 py-0.5 rounded-full">Soon</span></div>
            </div>
        </div>

        <div class="p-4 border-t border-white/5">
            <div class="flex items-center gap-3 p-2">
                <div class="w-8 h-8 bg-slate-800 rounded-full flex items-center justify-center border border-white/10"><i class="ph ph-user"></i></div>
                <div>
                    <div class="text-xs font-bold text-white">Student User</div>
                    <div class="text-[10px] text-indigo-400 font-mono">v3.0 // ONLINE</div>
                </div>
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gradient-to-br from-[#09090b] to-[#121218]">
        
        <header class="h-20 border-b border-white/5 flex items-center justify-between px-8 bg-black/20 backdrop-blur-md">
            <div>
                <h1 class="text-lg font-bold text-white tracking-tight">Active Study Session</h1>
                <div id="statusIndicator" class="hidden text-[10px] text-indigo-400 font-mono animate-pulse mt-1">PROCESSING_NEURAL_LINK...</div>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-400 font-medium">AI Persona:</span>
                <select id="personaSelect" class="bg-[#18181f] border border-white/10 text-white text-xs rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 cursor-pointer">
                    <option value="chill">Supportive Tutor</option>
                    <option value="strict">Strict Nigerian Lecturer</option>
                </select>
            </div>
        </header>

        <div class="flex-1 flex overflow-hidden p-6 gap-6">
            
            <section class="w-[45%] flex flex-col gap-4">
                <div class="glass-panel p-5 flex-1 flex flex-col flex flex-col gap-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-xs uppercase tracking-widest font-bold text-indigo-400"><i class="ph ph-books mr-1"></i> Context Source</h3>
                        <div class="flex gap-2">
                            <label class="cursor-pointer bg-[#18181f] hover:bg-slate-800 text-slate-300 px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center gap-2 border border-white/10">
                                <i class="ph ph-image text-sm"></i> Image
                                <input type="file" id="imageInput" accept="image/*" class="hidden" onchange="attachImage()">
                            </label>
                            <label class="cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/20">
                                <i class="ph ph-file-pdf text-sm"></i> PDF
                                <input type="file" id="pdfInput" accept=".pdf" class="hidden" onchange="extractPDFText()">
                            </label>
                        </div>
                    </div>
                    
                    <textarea id="sourceText" class="flex-1 bg-black/40 p-4 rounded-xl focus:outline-none focus:ring-1 focus:ring-indigo-500 text-slate-300 leading-relaxed resize-none text-sm border border-white/5" placeholder="Paste your lecture notes here, or upload a PDF/Image for the AI to analyze..."></textarea>
                    
                    <div id="imagePreviewContainer" class="hidden bg-indigo-500/10 p-3 rounded-lg border border-indigo-500/20 flex justify-between items-center">
                        <span class="text-xs text-indigo-300 font-mono flex items-center gap-2"><i class="ph ph-check-circle"></i> Image attached.</span>
                        <button onclick="removeImage()" class="text-red-400 text-xs hover:text-red-300">Remove</button>
                    </div>
                </div>

                <div class="glass-panel p-4 grid grid-cols-2 gap-3">
                    <button onclick="triggerAction('mindmap')" class="bg-indigo-600/10 hover:bg-indigo-600/30 border border-indigo-500/20 text-indigo-300 py-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                        <i class="ph ph-tree-structure text-lg"></i> Draw Mind Map
                    </button>
                    <button onclick="alert('Mock Exams coming in the next update!')" class="bg-purple-600/10 hover:bg-purple-600/30 border border-purple-500/20 text-purple-300 py-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                        <i class="ph ph-exam text-lg"></i> Generate Quiz
                    </button>
                </div>
            </section>

            <section class="w-[55%] flex flex-col glass-panel overflow-hidden">
                <div id="chatBox" class="flex-1 overflow-y-auto p-6 space-y-6 scroll-smooth">
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex-shrink-0 flex items-center justify-center text-white shadow-lg"><i class="ph ph-robot"></i></div>
                        <div class="bg-[#18181f] p-4 rounded-2xl rounded-tl-sm text-sm leading-relaxed max-w-[85%] text-slate-300 border border-white/5 shadow-sm">
                            Welcome to Nexus. Load up your study materials on the left, and let's get to work. No distractions.
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t border-white/5 bg-[#09090b]">
                    <div class="bg-[#18181f] flex items-center p-1.5 gap-2 pr-2 border border-white/10 rounded-2xl shadow-inner focus-within:border-indigo-500/50 transition-colors">
                        <input type="text" id="userInput" class="flex-1 bg-transparent p-3 text-sm focus:outline-none text-white pl-4" placeholder="Ask Nexus anything about your notes...">
                        <button onclick="triggerAction('chat')" id="sendBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white p-2.5 rounded-xl transition shadow-lg shadow-indigo-600/20">
                            <i class="ph ph-paper-plane-right text-lg"></i>
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script>
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
            const status = document.getElementById('statusIndicator');
            const sourceArea = document.getElementById('sourceText');
            if (!file) return;

            status.classList.remove('hidden');
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
                    alert("I couldn't read this PDF.");
                } finally {
                    status.classList.add('hidden');
                }
            };
            reader.readAsArrayBuffer(file);
        }

        async function triggerAction(actionType) {
            const notes = document.getElementById('sourceText').value;
            let question = document.getElementById('userInput').value;
            const chatBox = document.getElementById('chatBox');
            const persona = document.getElementById('personaSelect').value; // Get selected persona
            
            if(!notes && !attachedImageBase64) return alert("Please provide some context (notes or image) first.");
            if(actionType === 'chat' && !question) return alert("Please type a question.");
            if(actionType === 'mindmap') question = "Generate a Mind Map";

            // Add user message to chat
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
            formData.append('persona', persona); // Send persona to backend
            
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
                                <div class="bg-[#18181f] p-4 text-sm max-w-[95%] text-slate-300 border border-white/5 rounded-2xl rounded-tl-sm w-full overflow-x-auto shadow-sm">
                                    <p class="mb-4 text-xs text-indigo-400 font-bold uppercase tracking-wider">Generated Mind Map</p>
                                    <div id="${uniqueId}" class="flex justify-center bg-black/40 p-6 rounded-xl border border-white/5"></div>
                                </div>
                            </div>`;
                        
                        const { svg } = await window.mermaid.render('graph-' + uniqueId, mermaidCode);
                        document.getElementById(uniqueId).innerHTML = svg;
                    }
                } else {
                    chatBox.innerHTML += `
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex-shrink-0 flex items-center justify-center text-white shadow-lg"><i class="ph ph-robot"></i></div>
                            <div class="bg-[#18181f] p-4 rounded-2xl rounded-tl-sm text-sm leading-relaxed max-w-[85%] text-slate-300 border border-white/5 shadow-sm">
                                ${text}
                            </div>
                        </div>`;
                }

            } catch (err) {
                chatBox.innerHTML += `<div class="text-red-500 text-xs text-center font-mono uppercase tracking-widest my-4 bg-red-500/10 py-2 rounded-lg border border-red-500/20">ERROR_CONNECTING_TO_NEXUS_CORE</div>`;
                console.error(err);
            } finally {
                document.getElementById('statusIndicator').classList.add('hidden');
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }
    </script>
</body>
</html>

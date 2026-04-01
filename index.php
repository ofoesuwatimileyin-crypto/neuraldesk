<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeuralDesk | AI Workspace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f1115; color: #e2e8f0; }
        .glass { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>
<body class="h-screen flex flex-col overflow-hidden">

    <nav class="border-b border-white/5 p-4 flex justify-between items-center bg-[#0f1115] z-10">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center font-bold text-white shadow-lg shadow-blue-600/20">N</div>
            <span class="font-extrabold tracking-tighter text-xl text-white uppercase">NEURAL<span class="text-blue-500">DESK</span></span>
        </div>
        <div class="flex items-center gap-4">
            <div id="statusIndicator" class="hidden text-[10px] text-blue-400 font-mono animate-pulse uppercase">PROCESSING...</div>
            <div class="text-xs text-slate-500 font-mono uppercase tracking-widest">v2.2 // VISION_ACTIVE</div>
        </div>
    </nav>

    <main class="flex-1 flex overflow-hidden">
        
        <section class="w-1/2 p-6 border-r border-white/5 flex flex-col gap-4 bg-[#0f1115]">
            <div class="flex justify-between items-center">
                <h3 class="text-xs uppercase tracking-widest font-bold text-blue-400">Sources / Context</h3>
                
                <div class="flex gap-2">
                    <label class="cursor-pointer bg-slate-800 hover:bg-slate-700 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center gap-2 border border-white/10">
                        <span>📷 ADD IMAGE</span>
                        <input type="file" id="imageInput" accept="image/*" class="hidden" onchange="attachImage()">
                    </label>

                    <label class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center gap-2 shadow-lg shadow-blue-600/20">
                        <span>📄 UPLOAD PDF</span>
                        <input type="file" id="pdfInput" accept=".pdf" class="hidden" onchange="extractPDFText()">
                    </label>
                </div>
            </div>
            
            <textarea id="sourceText" class="flex-1 glass p-6 focus:outline-none focus:ring-1 focus:ring-blue-500 text-slate-300 leading-relaxed resize-none text-sm border-white/5" placeholder="Paste notes, extract a PDF, or attach an image to feed the AI..."></textarea>
            
            <div id="imagePreviewContainer" class="hidden glass p-3 border-white/5 flex justify-between items-center">
                <span class="text-xs text-blue-400 font-mono">Image attached and ready for analysis.</span>
                <button onclick="removeImage()" class="text-red-400 text-xs hover:text-red-300">Remove</button>
            </div>
        </section>

        <section class="w-1/2 flex flex-col bg-[#0b0d10]">
            <div id="chatBox" class="flex-1 overflow-y-auto p-8 space-y-6">
                <div class="flex gap-4">
                    <div class="w-8 h-8 rounded-full bg-slate-800 flex-shrink-0 flex items-center justify-center text-xs border border-white/10 shadow-sm">🤖</div>
                    <div class="glass p-4 text-sm leading-relaxed max-w-[85%] text-slate-300 border-white/5">
                        Vision module is online. Attach a diagram, an x-ray, or a textbook photo, and ask me what you want to know. 
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-white/5 bg-[#0f1115]">
                <div class="glass flex items-center p-2 gap-2 pr-4 border-white/10">
                    <input type="text" id="userInput" class="flex-1 bg-transparent p-3 text-sm focus:outline-none text-white" placeholder="Ask a question about your text or image...">
                    <button onclick="askAI()" id="sendBtn" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-xl transition shadow-lg shadow-blue-600/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </div>
            </div>
        </section>
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
                    alert("Omo, I couldn't read this PDF.");
                } finally {
                    status.classList.add('hidden');
                }
            };
            reader.readAsArrayBuffer(file);
        }

        async function askAI() {
            const notes = document.getElementById('sourceText').value;
            const question = document.getElementById('userInput').value;
            const chatBox = document.getElementById('chatBox');
            const btn = document.getElementById('sendBtn');

            if(!notes && !attachedImageBase64) return alert("Give me text or an image to look at!");
            if(!question) return alert("You forgot to ask a question!");

            chatBox.innerHTML += `
                <div class="flex gap-4 justify-end">
                    <div class="bg-blue-600/20 border border-blue-500/30 p-4 rounded-2xl text-sm max-w-[85%] text-blue-100">
                        ${question}
                        ${attachedImageBase64 ? '<br><span class="text-[10px] text-blue-400 mt-2 block">[Image Attached]</span>' : ''}
                    </div>
                </div>`;
            
            document.getElementById('userInput').value = "";
            chatBox.scrollTop = chatBox.scrollHeight;

            btn.disabled = true;
            document.getElementById('statusIndicator').classList.remove('hidden');

            const formData = new FormData();
            formData.append('notes', notes);
            formData.append('question', question);
            if (attachedImageBase64) {
                formData.append('image', attachedImageBase64);
                formData.append('mime', attachedImageMime);
            }

            try {
                const response = await fetch('process.php', { method: 'POST', body: formData });
                const text = await response.text();

                chatBox.innerHTML += `
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex-shrink-0 flex items-center justify-center text-xs text-white shadow-lg shadow-blue-600/20">🤖</div>
                        <div class="glass p-4 text-sm leading-relaxed max-w-[85%] text-slate-300 border-white/5">
                            ${text}
                        </div>
                    </div>`;
            } catch (err) {
                chatBox.innerHTML += `<div class="text-red-500 text-xs text-center font-mono uppercase tracking-widest">ERROR_CONNECTING_TO_CORE</div>`;
            } finally {
                btn.disabled = false;
                document.getElementById('statusIndicator').classList.add('hidden');
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }
    </script>
</body>
</html>
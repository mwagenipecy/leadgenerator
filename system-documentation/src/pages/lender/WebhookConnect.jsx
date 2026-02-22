import StepCard from '../../components/StepCard'

export default function WebhookConnect() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Webhook – Connect (PHP, Java, Node, Python)</h1>
      <p className="mb-8 text-slate-600">
        After you configure your webhook URL in <strong>Integrations</strong>, Fanikisha sends new leads (and optionally other events) to your endpoint via <strong>POST</strong> with a JSON body. Below are example handlers in <strong>PHP</strong>, <strong>Java</strong>, <strong>Node.js</strong>, and <strong>Python</strong> so you can receive and process webhooks in your preferred language.
      </p>

      <StepCard step={1} title="Configure in Fanikisha">
        Go to <strong>Integrations</strong> (or Webhook Integration) in the sidebar. Add your webhook URL (e.g. <code className="rounded bg-slate-100 px-1.5 py-0.5 text-sm">https://your-server.com/webhook/fanikisha</code>), set authentication (e.g. API key header) if required, and save. Fanikisha will POST to this URL when a new lead is submitted or when other configured events occur.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">PHP</h2>
      <pre className="mb-6 overflow-x-auto rounded-xl border border-slate-200 bg-slate-900 p-4 text-sm text-slate-100">
{`<?php
// Receive webhook from Fanikisha (e.g. in Laravel route or plain PHP)
$payload = file_get_contents('php://input');
$data = json_decode($payload, true);

if ($data && isset($data['event']) && $data['event'] === 'application.submitted') {
    $applicationId = $data['application_id'] ?? null;
    $applicant = $data['applicant'] ?? [];
    $loan = $data['loan'] ?? [];
    // Save to DB, send to CRM, etc.
    // ...
}

http_response_code(200);
header('Content-Type: application/json');
echo json_encode(['received' => true, 'message' => 'Lead received']);
`}
      </pre>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Java (Spring Boot)</h2>
      <pre className="mb-6 overflow-x-auto rounded-xl border border-slate-200 bg-slate-900 p-4 text-sm text-slate-100">
{`@PostMapping("/webhook/fanikisha")
public ResponseEntity<Map<String, Object>> webhook(@RequestBody Map<String, Object> payload) {
    String event = (String) payload.get("event");
    if ("application.submitted".equals(event)) {
        Integer applicationId = (Integer) payload.get("application_id");
        Map<String, Object> applicant = (Map<String, Object>) payload.get("applicant");
        Map<String, Object> loan = (Map<String, Object>) payload.get("loan");
        // Save to DB, send to CRM, etc.
    }
    return ResponseEntity.ok(Map.of("received", true, "message", "Lead received"));
}
`}
      </pre>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Node.js (Express)</h2>
      <pre className="mb-6 overflow-x-auto rounded-xl border border-slate-200 bg-slate-900 p-4 text-sm text-slate-100">
{`app.post('/webhook/fanikisha', express.json(), (req, res) => {
  const { event, application_id, applicant, loan } = req.body;
  if (event === 'application.submitted') {
    // Save to DB, send to CRM, etc.
    console.log('New lead:', application_id, applicant, loan);
  }
  res.status(200).json({ received: true, message: 'Lead received' });
});
`}
      </pre>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Python (Flask)</h2>
      <pre className="mb-6 overflow-x-auto rounded-xl border border-slate-200 bg-slate-900 p-4 text-sm text-slate-100">
{`from flask import Flask, request, jsonify

@app.route('/webhook/fanikisha', methods=['POST'])
def webhook():
    data = request.get_json()
    if data and data.get('event') == 'application.submitted':
        application_id = data.get('application_id')
        applicant = data.get('applicant', {})
        loan = data.get('loan', {})
        # Save to DB, send to CRM, etc.
    return jsonify(received=True, message='Lead received'), 200
`}
      </pre>

      <StepCard step={2} title="Respond with 200">
        Always respond with <strong>HTTP 200</strong> (or 2xx) and optionally a JSON body so Fanikisha knows the webhook was received. If you return an error (4xx/5xx), the platform may retry; ensure your endpoint is idempotent and can handle retries.
      </StepCard>
    </article>
  )
}

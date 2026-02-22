import { useState } from 'react'
import { Link } from 'react-router-dom'

const SCORE_RANGES = [
  { min: 300, max: 579, label: 'Poor', color: 'bg-red-500', text: 'Lenders may decline or charge higher rates. Focus on the steps below to improve.' },
  { min: 580, max: 669, label: 'Fair', color: 'bg-amber-500', text: 'You may qualify for some products but not the best rates. Improving will open more options.' },
  { min: 670, max: 739, label: 'Good', color: 'bg-lime-500', text: 'You are in a solid range. Many lenders will offer you competitive terms.' },
  { min: 740, max: 799, label: 'Very good', color: 'bg-green-500', text: 'Above average. You typically get better rates and higher limits.' },
  { min: 800, max: 850, label: 'Excellent', color: 'bg-emerald-600', text: 'Top tier. You get the best rates and terms from most lenders.' },
]

const SCORE_RANGES_FOR_GAUGE = [
  { min: 300, max: 579, label: 'Poor', color: '#ef4444' },
  { min: 580, max: 669, label: 'Fair', color: '#f59e0b' },
  { min: 670, max: 739, label: 'Good', color: '#84cc16' },
  { min: 740, max: 799, label: 'Very good', color: '#22c55e' },
  { min: 800, max: 850, label: 'Excellent', color: '#059669' },
]

const IMPROVE_LEVELS_STATIC = [
  { level: 1, title: 'Pay on time', tip: 'Pay every bill and loan instalment by the due date. Even one late payment can hurt your score. Set reminders or auto-debit.' },
  { level: 2, title: 'Keep credit utilization low', tip: 'Use less than 30% of your total credit limit (e.g. if limit is 1,000,000, try to owe under 300,000). Lower is better.' },
  { level: 3, title: "Don't close old accounts", tip: 'A longer credit history helps. Keep old cards or accounts open (even if you don\'t use them much) unless they have high fees.' },
  { level: 4, title: 'Limit new applications', tip: 'Too many loan or card applications in a short time can lower your score. Space out applications and only apply when you need to.' },
  { level: 5, title: 'Mix of credit types', tip: 'A healthy mix (e.g. one card, one loan) can help—but only if you manage them well. Don\'t take new credit just for the mix.' },
  { level: 6, title: 'Check and fix errors', tip: 'Get your report from the Credit Report page and dispute any wrong information (wrong name, wrong balance, duplicate accounts) with the bureau.' },
]

const IMPROVE_LEVELS = [
  { id: 1, title: 'Pay loan on time', icon: 'card', points: 28, tip: 'Pay every bill and loan instalment by the due date. Set reminders or auto-debit.' },
  { id: 2, title: 'Lower your balance', icon: 'arrowDown', points: 32, tip: 'Use less than 30% of your credit limit. Pay down existing debt.' },
  { id: 3, title: 'Keep old accounts open', icon: 'calendar', points: 18, tip: 'Longer credit history helps. Don\'t close old cards unless they have high fees.' },
  { id: 4, title: 'Fewer new applications', icon: 'ban', points: 22, tip: 'Space out loan or card applications. Only apply when you need to.' },
  { id: 5, title: 'Mix of credit types', icon: 'balance', points: 20, tip: 'A healthy mix (e.g. one card, one loan) can help—manage them well.' },
  { id: 6, title: 'Fix report errors', icon: 'search', points: 30, tip: 'Check your report and dispute wrong info with the bureau.' },
]

function LevelIcon({ name, className = 'h-5 w-5' }) {
  const c = className
  switch (name) {
    case 'card':
      return (
        <svg className={c} fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
        </svg>
      )
    case 'arrowDown':
      return (
        <svg className={c} fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
      )
    case 'calendar':
      return (
        <svg className={c} fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      )
    case 'ban':
      return (
        <svg className={c} fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
        </svg>
      )
    case 'balance':
      return (
        <svg className={c} fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
        </svg>
      )
    case 'search':
      return (
        <svg className={c} fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      )
    default:
      return null
  }
}

const MIN_SCORE = 300
const MAX_SCORE = 850
const START_SCORE = 518

function getScoreRange(score) {
  const r = SCORE_RANGES.find((s) => score >= s.min && score <= s.max)
  return r || SCORE_RANGES[SCORE_RANGES.length - 1]
}

// Gauge: semi-circle left = 300 (180°), right = 850 (0°). Needle at center; 0° = right, 180° = left.
function gaugeRotation(score) {
  const p = (score - MIN_SCORE) / (MAX_SCORE - MIN_SCORE)
  return 180 - 180 * Math.min(1, Math.max(0, p))
}

function CreditScoreGauge({ score }) {
  const rotation = gaugeRotation(score)
  const range = getScoreRange(score)
  return (
    <div className="flex flex-col items-center">
      <div className="relative mx-auto w-full max-w-[280px]">
        <svg viewBox="0 0 200 120" className="w-full" fill="none">
          {/* Background arc */}
          <path
            d="M 20 100 A 80 80 0 0 1 180 100"
            stroke="#e2e8f0"
            strokeWidth="14"
            fill="none"
            strokeLinecap="round"
          />
          {/* Colored segments – same arc as background: 300 at left (180°), 850 at right (0°) */}
          {SCORE_RANGES_FOR_GAUGE.map((r) => {
            const r2 = 80
            const cx = 100
            const cy = 100
            const toRad = (deg) => (deg * Math.PI) / 180
            const scoreToAngle = (s) => 180 - ((s - MIN_SCORE) / (MAX_SCORE - MIN_SCORE)) * 180
            const startAngleDeg = scoreToAngle(r.min)
            const endAngleDeg = scoreToAngle(r.max)
            const span = startAngleDeg - endAngleDeg
            if (span <= 0) return null
            const x1 = cx + r2 * Math.cos(toRad(startAngleDeg))
            const y1 = cy - r2 * Math.sin(toRad(startAngleDeg))
            const x2 = cx + r2 * Math.cos(toRad(endAngleDeg))
            const y2 = cy - r2 * Math.sin(toRad(endAngleDeg))
            const large = span > 180 ? 1 : 0
            const d = `M ${x1} ${y1} A ${r2} ${r2} 0 ${large} 1 ${x2} ${y2}`
            return <path key={r.min} d={d} stroke={r.color} strokeWidth="14" fill="none" strokeLinecap="round" opacity={0.9} />
          })}
          {/* Needle (smooth transition) */}
          <g style={{ transform: `translate(100px, 100px) rotate(${rotation}deg)`, transition: 'transform 0.5s ease-out' }}>
            <line x1="0" y1="0" x2="-70" y2="0" stroke="#0f172a" strokeWidth="4" strokeLinecap="round" />
            <circle cx="0" cy="0" r="8" fill="#C40F11" />
          </g>
        </svg>
        <div className="absolute bottom-0 left-0 right-0 flex justify-between px-4 text-xs font-medium text-slate-500" dir="ltr">
          <span style={{ position: 'absolute', left: '1rem' }}>300</span>
          <span style={{ position: 'absolute', right: '1rem' }}>850</span>
        </div>
      </div>
      <div className="mt-4 flex items-center gap-3">
        <span className="text-3xl font-bold tabular-nums text-slate-900">{score}</span>
        <span className={`rounded-full px-3 py-1 text-sm font-semibold text-white ${range.color}`}>{range.label}</span>
      </div>
    </div>
  )
}

export default function CreditScore() {
  const [score, setScore] = useState(START_SCORE)
  const [scoreInput, setScoreInput] = useState(String(START_SCORE))
  const [completedLevels, setCompletedLevels] = useState(new Set())
  const [scoreKey, setScoreKey] = useState(0)
  const [floatingPoints, setFloatingPoints] = useState(null)
  const [levelCelebration, setLevelCelebration] = useState(null)
  const [rangeMessage, setRangeMessage] = useState(null)

  const range = getScoreRange(score)
  const progressPercent = (completedLevels.size / IMPROVE_LEVELS.length) * 100

  const applyScoreInput = () => {
    const n = parseInt(scoreInput, 10)
    if (!Number.isNaN(n)) {
      const clamped = Math.min(MAX_SCORE, Math.max(MIN_SCORE, n))
      setScore(clamped)
      setScoreInput(String(clamped))
      setScoreKey((k) => k + 1)
    }
  }

  const handleLevelComplete = (level) => {
    if (completedLevels.has(level.id)) return
    const newScore = Math.min(MAX_SCORE, score + level.points)
    const oldRange = getScoreRange(score)
    const newRange = getScoreRange(newScore)

    setFloatingPoints({ points: level.points, id: Date.now() })
    setCompletedLevels((prev) => new Set(prev).add(level.id))
    setScore(newScore)
    setScoreInput(String(newScore))
    setScoreKey((k) => k + 1)
    setLevelCelebration(level.title)
    setTimeout(() => setLevelCelebration(null), 1800)

    if (newRange.label !== oldRange.label) {
      setRangeMessage(`You're now in ${newRange.label} range!`)
      setTimeout(() => setRangeMessage(null), 3500)
    }
  }

  const resetSimulation = () => {
    setScore(START_SCORE)
    setScoreInput(String(START_SCORE))
    setCompletedLevels(new Set())
    setScoreKey((k) => k + 1)
    setRangeMessage(null)
    setLevelCelebration(null)
  }

  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Credit Score</h1>
      <p className="mb-8 text-slate-600">
        Your <strong className="text-brand-red">credit score</strong> is a number that summarises how likely you are to repay debt. Lenders use it to decide whether to approve your loan and at what rate. Understanding your score and how to improve it helps you get better loan offers. You can request your report and score from the <Link to="/borrower/credit-report" className="text-brand-red hover:underline">Credit Report</Link> page.
      </p>

      {/* Previous interface: What does credit score mean? */}
      <section className="mb-10">
        <h2 className="mb-4 text-xl font-semibold text-brand-red">What does credit score mean?</h2>
        <p className="mb-4 text-slate-600">
          A <strong>credit score</strong> is a number (usually between 300 and 850) that reflects your credit behaviour: payment history, how much you owe, length of credit history, new credit, and types of credit. The credit bureau calculates it from your report. <strong>Higher score</strong> usually means lower risk to the lender, so you get better approval chances and better interest rates. <strong>Lower score</strong> can mean fewer offers or higher rates. The score is not permanent—you can improve it over time by following the tips below.
        </p>
      </section>

      {/* Previous interface: Score number ranges */}
      <section className="mb-10">
        <h2 className="mb-4 text-xl font-semibold text-brand-red">Score number ranges</h2>
        <p className="mb-4 text-slate-600">
          Ranges below are typical. Your bureau may use slightly different bands. Higher is better.
        </p>
        <div className="space-y-3">
          {SCORE_RANGES.map((item) => (
            <div
              key={item.min}
              className="flex flex-wrap items-center gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm"
            >
              <div className={`flex h-10 w-24 items-center justify-center rounded-lg ${item.color} text-sm font-bold text-white`}>
                {item.min} – {item.max}
              </div>
              <div className="font-semibold text-slate-900">{item.label}</div>
              <p className="w-full text-sm text-slate-600 sm:flex-1">{item.text}</p>
            </div>
          ))}
        </div>
      </section>

      {/* Previous interface: How to improve – static level cards */}
      <section className="mb-10">
        <h2 className="mb-4 text-xl font-semibold text-brand-red">How to improve your score</h2>
        <p className="mb-6 text-slate-600">
          Think of improving your score like levelling up. Each level is a habit to master. The more you follow these, the better your score can become over time.
        </p>
        <div className="grid gap-4 sm:grid-cols-2">
          {IMPROVE_LEVELS_STATIC.map((item) => (
            <div
              key={item.level}
              className="rounded-xl border-2 border-brand-red/30 bg-white p-5 shadow-sm transition hover:border-brand-red/60 hover:shadow-md"
            >
              <div className="mb-3 flex items-center gap-3">
                <span className="flex h-10 w-10 items-center justify-center rounded-full bg-brand-red text-lg font-bold text-white">
                  {item.level}
                </span>
                <span className="text-lg font-semibold text-slate-900">Level {item.level}: {item.title}</span>
              </div>
              <p className="text-sm text-slate-600">{item.tip}</p>
            </div>
          ))}
        </div>
      </section>

      <p className="mb-10 rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
        <strong className="text-brand-red">Tip:</strong> Improvement takes time. Pay on time every month, keep balances low, and check your report once in a while. Small, consistent steps will move your score in the right direction.
      </p>

      {/* New section below: Gauge + simulator + increase score */}
      <section className="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-6 sm:p-8">
        <h2 className="mb-2 text-xl font-semibold text-brand-red">Credit score simulator</h2>
        <p className="mb-8 text-sm text-slate-600">
          Use the gauge and the options below to see how your score can change. Enter a score, use the slider, or complete the actions to increase your score in the simulation.
        </p>

        {/* Gauge / speedometer */}
        <div className="relative mb-10 flex justify-center rounded-2xl bg-white py-8">
          {floatingPoints && (
            <span
              key={floatingPoints.id}
              className="animate-float-up absolute left-1/2 top-1/3 z-10 -translate-x-1/2 -translate-y-1/2 text-2xl font-bold text-brand-red"
            >
              +{floatingPoints.points}
            </span>
          )}
          <CreditScoreGauge score={score} />
        </div>

        {/* User input: set score directly */}
        <div className="mb-10 rounded-xl border border-slate-200 bg-white p-6">
          <h3 className="mb-3 font-semibold text-slate-900">Set your score (300 – 850)</h3>
          <div className="flex flex-wrap items-center gap-4">
            <input
              type="number"
              min={MIN_SCORE}
              max={MAX_SCORE}
              value={scoreInput}
              onChange={(e) => setScoreInput(e.target.value)}
              onBlur={applyScoreInput}
              onKeyDown={(e) => e.key === 'Enter' && applyScoreInput()}
              className="w-28 rounded-lg border border-slate-300 px-3 py-2 text-center text-lg font-bold tabular-nums focus:border-brand-red focus:outline-none focus:ring-2 focus:ring-brand-red/20"
            />
            <button
              type="button"
              onClick={applyScoreInput}
              className="rounded-lg bg-brand-red px-4 py-2 text-sm font-semibold text-white hover:bg-brand-darkRed"
            >
              Apply
            </button>
            <div className="w-full sm:w-auto">
              <label className="mb-1 block text-xs font-medium text-slate-500">Or use slider</label>
              <input
                type="range"
                min={MIN_SCORE}
                max={MAX_SCORE}
                value={score}
                onChange={(e) => {
                  const v = Number(e.target.value)
                  setScore(v)
                  setScoreInput(String(v))
                  setScoreKey((k) => k + 1)
                }}
                className="h-2 w-full max-w-xs accent-brand-red"
              />
            </div>
          </div>
        </div>

        {/* Increase credit score – action buttons */}
        <div className="mb-6">
          <h3 className="mb-2 font-semibold text-slate-900">Increase your score – complete actions</h3>
          <p className="mb-4 text-sm text-slate-600">
            Click each action when you do it in real life. Watch the gauge and score go up! (Simulation only.)
          </p>
          <div className="mb-4 flex items-center justify-between text-sm">
            <span className="font-medium text-slate-600">Progress</span>
            <span className="font-semibold text-brand-red">{completedLevels.size} / {IMPROVE_LEVELS.length} completed</span>
          </div>
          <div className="mb-4 h-2 overflow-hidden rounded-full bg-slate-200">
            <div
              className="h-full rounded-full bg-brand-red transition-all duration-700 ease-out"
              style={{ width: `${progressPercent}%` }}
            />
          </div>
        </div>

        <div className="grid gap-4 sm:grid-cols-2">
          {IMPROVE_LEVELS.map((level) => {
            const done = completedLevels.has(level.id)
            const celebrating = levelCelebration === level.title
            return (
              <div
                key={level.id}
                className={`rounded-xl border bg-white p-5 transition-all duration-300 ${
                  done ? 'border-green-400 bg-green-50/50' : 'border-slate-200 hover:border-slate-300'
                } ${celebrating ? 'animate-level-unlock ring-2 ring-brand-red/50' : ''}`}
              >
                <div className="mb-3 flex items-center justify-between gap-3">
                  <div className="flex items-center gap-3">
                    <span className={`flex h-10 w-10 items-center justify-center rounded-full ${done ? 'bg-green-500 text-white' : 'bg-slate-600 text-white'}`}>
                      {done ? <span className="text-lg font-bold">✓</span> : <LevelIcon name={level.icon} className="h-5 w-5 text-white" />}
                    </span>
                    <span className="font-semibold text-slate-900">Level {level.id}: {level.title}</span>
                  </div>
                  {done && (
                    <span className="rounded-full bg-green-500 px-2 py-0.5 text-xs font-bold text-white">+{level.points}</span>
                  )}
                </div>
                <p className="mb-4 text-sm text-slate-600">{level.tip}</p>
                {done ? (
                  <div className="flex items-center gap-2 text-green-600">
                    <span className="text-lg">✓</span>
                    <span className="text-sm font-semibold">Completed</span>
                  </div>
                ) : (
                  <button
                    type="button"
                    onClick={() => handleLevelComplete(level)}
                    className="w-full rounded-lg bg-brand-red px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-darkRed active:scale-[0.98]"
                  >
                    I did this! +{level.points}
                  </button>
                )}
              </div>
            )
          })}
        </div>

        {rangeMessage && (
          <p className="mt-4 animate-level-unlock rounded-lg bg-brand-red/10 px-4 py-2 text-center text-sm font-semibold text-brand-red">
            {rangeMessage}
          </p>
        )}

        <div className="mt-6 flex justify-center">
          <button
            type="button"
            onClick={resetSimulation}
            className="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
          >
            Reset simulation
          </button>
        </div>
      </section>
    </article>
  )
}

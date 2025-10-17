export interface BettingRecord {
  id: number;
  user: string;
  game: string;
  provider: string;
  game_type: string;
  bet: number;
  transaction_id: string;
  win: number;
  before_balance: number;
  after_balance: number;
  round_id: string;
}
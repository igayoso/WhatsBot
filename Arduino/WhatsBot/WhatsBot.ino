const int Rate = 9600;

void setup()
{
  Serial.begin(Rate);
  
  pinMode(12, OUTPUT);
  pinMode(13, OUTPUT);
}

void loop()
{
  char Buffer[128];
  
  int Readed = Serial.readBytesUntil(0x10, Buffer, 128);
  
  if(Readed > 0)
  {
    Blink(13, 5, false);
    
    String Command = String(Buffer).substring(0, Readed);
    Command.toLowerCase();
    Command.trim();
    
    int Offset = Command.indexOf(0x20);
    String Param = Command.substring(Offset + 1);
    int IntParam = Param.toInt();
    Command = Command.substring(0, Offset);
    
    if(Command == "pmi")
    {
      if(IsAvailablePin(IntParam))
        pinMode(IntParam, INPUT);
    }
    else if(Command == "pmo")
    {
      if(IsAvailablePin(IntParam))
        pinMode(IntParam, OUTPUT);
    }
    else if(Command == "dwh")
    {
      if(IsAvailablePin(IntParam))
        digitalWrite(IntParam, HIGH);
    }
    else if(Command == "dwl")
    {
      if(IsAvailablePin(IntParam))
        digitalWrite(IntParam, LOW);
    }
  }
  else
    Blink(12, 100, true);
}

void Blink(int Pin, int Delay, boolean WithEnd)
{
  digitalWrite(Pin, HIGH);
  delay(Delay);
  digitalWrite(Pin, LOW);
  if(WithEnd)
    delay(Delay);
}

boolean IsAvailablePin(int Pin)
{
  return Pin > 1 && Pin < 12;
}

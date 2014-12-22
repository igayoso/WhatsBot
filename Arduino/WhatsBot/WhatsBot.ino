const int Rate = 9600;
const char CommandEnd = 0x26; // Ampersand
const char Separator = 0x20; // Space

void setup()
{
  Serial.begin(Rate);
  
  pinMode(LED_BUILTIN, OUTPUT);
}

void loop()
{
  char Buffer[128];
  
  int Readed = Serial.readBytesUntil(0x26, Buffer, 128); // 0x10
  
  if(Readed > 0)
  {
    Blink();
    
    String Command = String(Buffer).substring(0, Readed);
    
    int Offset = Command.indexOf(Separator);
    String Object = Command.substring(0, Offset);
    String Params = Command.substring(Offset + 1);
    
    if(Object == "pin")
      Serial.println(Pin(Params));
  }
}

void Blink()
{
  digitalWrite(LED_BUILTIN, HIGH);
  delay(5);
  digitalWrite(LED_BUILTIN, LOW);
}

String Pin(String Params)
{
  int Offset = Params.indexOf(Separator);
  String Command = Params.substring(0, Offset);
  String SubParams = Params.substring(Offset + 1);
  
  if(Command == "mode")
    return PinMode(SubParams);
  else if(Command == "write")
    return PinWrite(SubParams);
  //else if(Command == "read")
  //  ;
  
  return "pin_action_invalid";
}

String PinMode(String Params)
{
  int Offset = Params.indexOf(Separator);
  int Pin = Params.substring(0, Offset).toInt();
  String Mode = Params.substring(Offset + 1);
  
  if(IsAvailablePin(Pin))
  {
    if(Mode == "output")
      pinMode(Pin, OUTPUT);
    else if(Mode == "input")
      pinMode(Pin, INPUT);
    else
      return "pin_mode_invalid";
    
    return "pin_mode_setted";
  }
  
  return "pin_unavailable";
}

String PinWrite(String Params)
{
  int Offset = Params.indexOf(Separator);
  int Pin = Params.substring(0, Offset).toInt();
  String Mode = Params.substring(Offset + 1);
  
  if(IsAvailablePin(Pin))
  {
    if(Mode == "high")
      digitalWrite(Pin, HIGH);
    else if(Mode == "low")
      digitalWrite(Pin, LOW);
    //else if(Mode == "toggle")
    //  ;
    else
      return "pin_value_invalid";
    
    return "pin_value_setted";
  }
  
  return "pin_unavailable";
}

boolean IsAvailablePin(int Pin)
{
  return Pin > 1 && Pin < 14; // < 13?
}

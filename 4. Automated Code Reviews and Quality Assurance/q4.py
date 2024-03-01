import re
import json

def scan(code, vuln):
    lst = []

    for i in vuln:
        cveid = i["cveid"]
        threat = i["threat"]
        summary = i["summary"]
        fix_versions = i["fixVersions"]["base"]

        pattern = re.compile(fr'\b({"|".join(re.escape(word.lower()) for word in summary.split())})\b', re.IGNORECASE)
        matches = pattern.finditer(code)
        for j in matches:
            result = {
                "cveid": cveid,
                "threat": threat,
                "line": code.count('\n', 0, j.start()) + 1,
                "column": j.start() - code.rfind('\n', 0, j.start()),
                "match": j.group()
            }
            lst.append(result)

    return lst

with open("php_versions_vulnerabilities.json", "r") as json_file:
    vuln = json.load(json_file)["checks"]
file = input("Enter the file name : ")
with open(file, 'r') as f:
    code = f.read()
lst = scan(code, vuln)
if lst:
    print("vuln found:")
    for i in lst:
        print(f"CVEID: {i['cveid']} - Threat Level: {i['threat']} - Line {i['line']}, Column {i['column']}: {i['match']}")
else:
    print("No vuln found.")

#include <bits/stdc++.h>

using namespace std;

typedef long long     LL;
typedef pair<int,int> pii;

double PI  = acos(-1);
double EPS = 1e-7;
int INF    = 1000000000;
LL INFLL   = 1000000000000000000LL;

#define fi            first
#define se            second
#define mp            make_pair
#define pb            push_back

#define input(in)     freopen(in,"r",stdin)
#define output(out)   freopen(out,"w",stdout)

#define MIN(a, b)     (a) = min((a), (b))
#define MAX(a, b)     (a) = max((a), (b))

#define RESET(a, b)   memset(a,b,sizeof(a))
#define ALL(a)        (a).begin(), (a).end()
#define SIZE(a)       (int)a.size()
#define SORT(a)       sort(ALL(a))
#define UNIQUE(a)     (a).erase( unique( ALL(a) ), (a).end() )
#define FOR(a, b, c)  for (int (a)=(b); (a)<=(c); (a)++)
#define FORD(a, b, c) for (int (a)=(b); (a)>=(c); (a)--)
#define FORIT(a, b)   for (__typeof((b).begin()) a=(b).begin(); a!=(b).end(); a++)

int mx[8] = {-1,1,0,0,-1,-1,1,1};
int my[8] = {0,0,-1,1,-1,1,-1,1};

// ----- //

/*
	// old type
	$('td:nth-child(15)').css("display", "none");
	$('td:nth-child(14)').css("display", "none");
	$('td:nth-child(13)').css("display", "none");
	$('td:nth-child(12)').css("display", "none");
	$('td:nth-child(11)').css("display", "none");
	$('td:nth-child(10)').css("display", "none");
	$('td:nth-child(9)').css("display", "none");
	$('td:nth-child(8)').css("display", "none");
	$('td:nth-child(7)').css("display", "none");
	$('td:nth-child(6)').css("display", "none");
	$('td:nth-child(5)').css("display", "none");
	$('td:nth-child(4)').css("display", "none");
	// new type
	$('td:nth-child(15)').css("display", "none");
	$('td:nth-child(14)').css("display", "none");
	$('td:nth-child(13)').css("display", "none");
	$('td:nth-child(12)').css("display", "none");
	$('td:nth-child(11)').css("display", "none");
	$('td:nth-child(10)').css("display", "none");
	$('td:nth-child(9)').css("display", "none");
	$('td:nth-child(8)').css("display", "none");
	$('td:nth-child(7)').css("display", "none");
	$('td:nth-child(6)').css("display", "none");
	$('td:nth-child(5)').css("display", "none");
	$('td:nth-child(3)').css("display", "none");
*/

char contestname[1005];
char data[10005];
char handle[10005];

string en_month[12] = {"january","february","march","april","may","june","july","august","september","october","november","december"};
string id_month[12] = {"januari","februari","maret","april","mei","juni","juli","agustus","september","oktober","november","desember"};

string translate(string name)
{
	int len = name.length();
	string year = "";
	year += name.back();
	name.pop_back();
	year += name.back();
	name.pop_back();
	year += name.back();
	name.pop_back();
	year += name.back();
	name.pop_back();
	reverse(year.begin(),year.end());
	
	
	FOR(a,0,11)
	{
		if (name == id_month[a]) name = en_month[a];
	}
	name += year;
	return name;
}

int main()
{
	scanf("%s\n",contestname);
	assert(isalpha(contestname[0]));

	string name = translate(contestname);
	string out = name+".dbs";
	output(out.c_str());
	
	int rank;
	double score;
	while(scanf("%d %s",&rank,handle) != EOF)
	{
		while(getchar() != ')');
		scanf("%lf",&score);
		if (score > 0) printf("INSERT INTO %s VALUES (%d,\"%s\",%.2lf,NULL);\n",name.c_str(),rank,handle,score);
	}
}
